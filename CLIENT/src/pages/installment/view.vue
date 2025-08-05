<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import { useI18n } from 'vue-i18n'
import Swal from 'sweetalert2'
import {
  VContainer,
  VRow,
  VCol,
  VBtn,
  VCard,
  VCardText,
  VProgressCircular,
  VIcon,
} from 'vuetify/lib/components/index.mjs'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()

const payments = ref([])
const loadingFetch = ref(false)
const loadingSave = ref(false)
const installmentId = route.query.id

const showPaymentSchedule = ref(false)
const showPaymentModal = ref(false)
const selectedMonthIndex = ref(null)
const paymentsByMonth = ref([])

const togglePaymentSchedule = () => {
  showPaymentSchedule.value = !showPaymentSchedule.value
}

const viewMonthlyDetails = monthIndex => {
  selectedMonthIndex.value = monthIndex

  const baseDate = new Date(installment.due_date || new Date())
  baseDate.setMonth(baseDate.getMonth() + monthIndex)

  paymentsByMonth.value = payments.value.filter(p => {
    const payDate = new Date(p.payment_date)

    return payDate.getMonth() === baseDate.getMonth() && payDate.getFullYear() === baseDate.getFullYear()
  })

  showPaymentModal.value = true
}

const startDate = computed(() => {
  return new Date(installment.sale_date || new Date())
})

const paymentSchedule = computed(() => {
  const schedule = []
  const baseStart = startDate.value

  for (let i = 0; i < 36; i++) {
    const dueDate = new Date(baseStart)
    dueDate.setMonth(baseStart.getMonth() + i)

    const paymentsInMonth = payments.value.filter(p => {
      const pd = new Date(p.payment_date)

      return pd.getMonth() === dueDate.getMonth() && pd.getFullYear() === dueDate.getFullYear()
    })

    const totalPaid = paymentsInMonth.reduce((sum, p) => sum + Number(p.amount || 0), 0)
    const notes = paymentsInMonth
      .map(p => p.note)
      .filter(Boolean)
      .join(', ')

    const status =
      paymentsInMonth.length === 0 ? 'មិនទាន់បង់' : totalPaid >= installment.monthly_fee ? 'បានបង់' : 'បង់មួយភាគ'

    schedule.push({
      month: i + 1,
      due_date: dueDate.toISOString().slice(0, 10),
      paid_date: paymentsInMonth.length ? paymentsInMonth[paymentsInMonth.length - 1].payment_date : null,
      note: notes,
      amount: totalPaid || installment.monthly_fee,
      count: paymentsInMonth.length,
      status,
    })
  }

  return schedule
})

// Installment data
const installment = reactive({
  customer: {},
  total_price: 0,
  deposit: 0,
  paid_amount: 0,
  monthly_fee: 0,
  status: '',
  due_date: '',
  sale: { products: [], duration: 36 },
})

// Payment form
const payment = reactive({
  amount: 0,
  payment_date: new Date().toISOString().slice(0, 10),
  note: '',
  method: '',
  reference: '',
  payer_name: '',
  quantity_paid: 1,
})

// Error message
const error = reactive({ message: '' })

// Computed fields
const deposit = computed(() => Number(installment.deposit) || 0)
const paidAmount = computed(() => Number(installment.paid_amount) || 0)

// Install price from first product or fallback 0
const installPrice = computed(() => {
  const products = installment.sale?.products || []
  if (!products.length) return 0

  return Number(products[0].install_price) || 0
})

// Duration from sale or default 36 months
const duration = computed(() => {
  return Number(installment.sale?.duration) || 36
})

// Calculate total expected = install price * duration
const totalExpected = computed(() => installPrice.value * duration.value)

// Approximate payments count (number of installments paid)

// Total paid including the current payment form amount
const totalPaid = computed(() => paidAmount.value + Number(payment.amount))

// Remaining balance = total expected - total paid
const balance = computed(() => {
  const remaining = totalExpected.value - totalPaid.value

  return remaining > 0 ? remaining : 0
})

// Percentage paid
const paidPercentage = computed(() => {
  if (totalExpected.value === 0) return 0

  return Math.min((totalPaid.value / totalExpected.value) * 100, 100)
})

// Fully paid check
const isFullyPaid = computed(() => installment.status === 'paid' || balance.value <= 0)

const isOnTime = computed(() => {
  if (!installment.due_date) return true
  const due = new Date(installment.due_date)
  const paymentDate = new Date(payment.payment_date)

  return paymentDate <= due
})

// Fetch payments history
const fetchPayments = async () => {
  if (!installmentId) return
  loadingFetch.value = true
  try {
    const res = await api.get(`/installments/${installmentId}/payments`)
    payments.value = res.data.data || []
  } catch (error) {
    console.error('❌ Failed to fetch payment history', error)
    Swal.fire('Error', t('Failed to fetch payment history'), 'error')
  } finally {
    loadingFetch.value = false
  }
}

// Fetch installment data
const fetchInstallment = async () => {
  if (!installmentId) {
    Swal.fire('❌ Error', t('Missing installment ID.'), 'error')
    error.message = 'Installment ID missing in route.'

    return
  }

  loadingFetch.value = true
  try {
    const res = await api.get(`/installments/${installmentId}`)
    if (res.data?.status === 200) {
      const data = res.data.data
      installment.customer = data.customer || {}
      installment.total_price = Number(data.total_price)
      installment.deposit = Number(data.deposit)
      installment.paid_amount = Number(data.paid_amount)
      installment.status = data.status
      installment.due_date = data.due_date
      installment.sale_date = data.sale_date
      installment.sale = data.sale || { products: [], duration: 36 }
      const installPrice = computed(() => {
        const products = installment.sale?.products || []
        if (!products.length) return 0

        return Number(products[0].install_price) || 0
      })

      if (!isFullyPaid.value && installPrice.value > 0) {
        payment.amount = installPrice.value
      }
    } else {
      throw new Error(res.data.message || 'Failed to fetch installment.')
    }
  } catch (err) {
    console.error('❌ Fetch error:', err)
    Swal.fire('❌ Error', t('Could not load installment data.'), 'error')
    error.message = 'Failed to fetch installment data.'
  } finally {
    loadingFetch.value = false
  }
}

// Submit payment
const submitPayment = async () => {
  if (isFullyPaid.value) {
    Swal.fire('✅ Fully Paid', t('This installment is already fully paid.'), 'info')

    return
  }

  loadingSave.value = true
  try {
    const payload = {
      ...payment,
      installment_id: installmentId,
    }
    console.log('🔍 Payload being sent:', payload)

    const res = await api.post(`/installments/${installmentId}/payments`, payload)

    if (res.data?.status === 200) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: t('Payment recorded successfully.'),
        showConfirmButton: false,
        timer: 1500,
      }).then(() => {
        router.push({
          name: 'installment-PaymentInvoice',
          query: { id: res.data.data.id },
        })
      })
    } else {
      throw new Error(res.data.message || 'Failed to record payment.')
    }
  } catch (error) {
    Swal.fire('❌ Error', error.response?.data?.message || t('Failed to record payment.'), 'error')
  } finally {
    loadingSave.value = false
  }
}

onMounted(() => {
  if (installmentId) {
    fetchPayments()
    fetchInstallment()
  }
})
watch(
  () => payment.amount,
  newAmount => {
    console.log('💡 installPrice:', installPrice.value)
    const qty = installPrice.value > 0 ? Math.floor(newAmount / installPrice.value) : 0
    payment.quantity_paid = qty > 0 ? qty : 1
    console.log('🧮 quantity_paid updated to:', payment.quantity_paid)
  },
)

const totalQuantityPaid = computed(() => {
  return payments.value.reduce((sum, p) => sum + Number(p.quantity_paid || 0), 0)
})
</script>

<template>
  <AppFormDetailTemplate
    :title="$t('Payment Place')"
    cols="12"
  >
    <div class="button-row flex justify-center">
      <VBtn
        color="primary"
        @click="togglePaymentSchedule"
      >
        {{ showPaymentSchedule ? t('Hide Payment Schedule') : t('Show Payment Schedule') }}
      </VBtn>
    </div>
    <div class="paymentForm">
      <div class="paymentInstall">
        <div class="">
          <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md border border-gray-200">
            <!-- Loading spinner -->
            <VCard v-if="loadingFetch">
              <VCardText>
                <VProgressCircular
                  indeterminate
                  color="primary"
                />
                <span class="ml-3">{{ $t('Loading data...') }}</span>
              </VCardText>
            </VCard>

            <div v-else>
              <div class="flex justify-center mb-4 mt-5">
                <div
                  class="progress"
                  :data-percentage="paidPercentage.toFixed(0)"
                >
                  <span class="progress-left"><span class="progress-bar"></span></span>
                  <span class="progress-right"><span class="progress-bar"></span></span>
                  <div class="progress-value">
                    <div class="text-lg text-center">
                      <h2>{{ paidPercentage.toFixed(0) }}%</h2>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-gray-50 rounded-lg p-3 mb-4 space-y-1 text-sm ps-5">
                <div class="container">
                  <div class="icon"><VIcon color="indigo">mdi-account</VIcon></div>
                  <div class="title">
                    <p class="names">{{ t('Customer Name') }}</p>
                    <h2 class="name">{{ installment.customer?.name || '-' }}</h2>
                  </div>
                </div>
                <div class="container">
                  <div class="icon"><VIcon color="indigo">mdi-arrow-down</VIcon></div>
                  <div class="title">
                    <p class="names">{{ t('Deposit') }}</p>
                    <h2 class="name">${{ deposit.toLocaleString() }}</h2>
                  </div>
                </div>
                <div class="container">
                  <div class="icon"><VIcon color="indigo">mdi-arrow-up</VIcon></div>
                  <div class="title">
                    <p class="names">{{ t('Already Paid') }}</p>
                    <h2 class="name">${{ paidAmount.toLocaleString() }}</h2>
                  </div>
                </div>
                <div class="container">
                  <div class="icon"><VIcon color="indigo">mdi-cash</VIcon></div>
                  <div class="title">
                    <p class="names">{{ t('Remaining Balance') }}</p>
                    <h2 class="name">${{ balance.toFixed(2) }}</h2>
                  </div>
                </div>
                <div class="container">
                  <div class="icon"><VIcon color="indigo">mdi-numeric-1-box-multiple</VIcon></div>
                  <div class="title">
                    <p class="names">{{ t('Payments Quantity') }}</p>
                    <h2 class="name">{{ totalQuantityPaid }}/36</h2>
                  </div>
                </div>
              </div>

              <VRow
                dense
                v-if="!isFullyPaid"
              >
                <VBtn
                  color="primary"
                  text
                  class="mt-1 monthly_fee"
                  @click="payment.amount = installPrice"
                >
                  {{ t('Payment Amount') }} (${{ installPrice.toLocaleString() }})
                </VBtn>

                <VBtn
                  color="primary"
                  text
                  class="mt-1 monthly_fee"
                >
                  {{ t('Date') }} ({{ payment.payment_date }})
                </VBtn>

                <VCol
                  cols="12"
                  class="pay_now"
                >
                  <VBtn
                    color="primary"
                    block
                    :loading="loadingSave"
                    :disabled="loadingSave"
                    @click="submitPayment"
                  >
                    {{ t('Pay Now') }}
                  </VBtn>
                </VCol>
              </VRow>
            </div>
          </div>
        </div>
      </div>
      <!-- List of Schedule Payment -->

      <div class="schedulePayment ps-5">
        <Transition name="fade">
          <div v-if="showPaymentSchedule">
            <VContainer>
              <h2 class="text-2xl font-bold mb-4 Payment_history">
                {{ t('Payment Schedule') }}
              </h2>
              <div class="payment-list-scroll">
                <VTable>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>{{ $t('Due Date') }}</th>
                      <th>{{ $t('Paid Date') }}</th>
                      <th>{{ $t('Price') }}</th>
                      <th>{{ $t('Month') }}</th>
                      <th>{{ $t('Status') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(item, index) in paymentSchedule"
                      :key="index"
                      :class="
                        item.status === 'បានបង់'
                          ? 'bg-green-50'
                          : item.status === 'បង់មួយភាគ'
                          ? 'bg-yellow-50'
                          : 'bg-red-50'
                      "
                    >
                      <td>{{ item.month }}</td>
                      <td>{{ item.due_date }}</td>
                      <td>{{ item.paid_date || '-' }}</td>
                      <td>${{ item.amount.toLocaleString() }}</td>
                      <td>{{ t('Months') }} {{ index + 1 }}</td>
                      <td>
                        <span
                          :style="{
                            color: item.status === 'បានបង់' ? 'blue' : item.status === 'បង់មួយភាគ' ? 'orange' : 'red',
                          }"
                        >
                          {{ item.status }}
                          <template v-if="item.count > 1"> (ត្រួត {{ item.count }} ដង) </template>
                        </span>
                      </td>
                      <td>
                        <VBtn
                          icon
                          density="compact"
                          @click="viewMonthlyDetails(index)"
                          v-if="item.status !== 'មិនទាន់បង់'"
                        >
                          <VIcon>mdi-eye</VIcon>
                        </VBtn>
                      </td>
                    </tr>
                  </tbody>
                </VTable>
              </div>
            </VContainer>
          </div>
        </Transition>
      </div>
    </div>

    <VDialog
      v-model="showPaymentModal"
      max-width="500"
    >
      <VCard>
        <VCardTitle class="text-lg font-semibold"> {{ t('Months') }} {{ selectedMonthIndex + 1 }} </VCardTitle>
        <VCardText>
          <VTable>
            <thead>
              <tr>
                <th>#</th>
                <th>{{ t('Date') }}</th>
                <th>{{ t('Price') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(p, i) in paymentsByMonth"
                :key="i"
              >
                <td>{{ i + 1 }}</td>
                <td>{{ p.payment_date }}</td>
                <td>${{ Number(p.amount).toLocaleString() }}</td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn
            color="primary"
            @click="showPaymentModal = false"
            >{{ t('Close') }}</VBtn
          >
        </VCardActions>
      </VCard>
    </VDialog>
  </AppFormDetailTemplate>
</template>

<style lang="scss">
$borderWidth: 20px;
$animationTime: 0.5s;
$border-color-default: #efefef;
$border-color-fill: #153abc;
$size: 120px;
$howManySteps: 100;

.progress {
  width: $size;
  height: $size;
  line-height: $size;
  background: none;
  margin: 0 auto;
  box-shadow: none;
  position: relative;

  &:after {
    content: '';
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: $borderWidth solid $border-color-default;
    position: absolute;
    top: 0;
    left: 0;
  }

  > span {
    width: 50%;
    height: 100%;
    overflow: hidden;
    position: absolute;
    top: 0;
    z-index: 1;
  }

  .progress-left {
    left: 0;
  }

  .progress-bar {
    width: 100%;
    height: 100%;
    background: none;
    border-width: $borderWidth;
    border-style: solid;
    position: absolute;
    top: 0;
    border-color: $border-color-fill;
  }

  .progress-left .progress-bar {
    left: 100%;
    border-top-right-radius: ($size / 2);
    border-bottom-right-radius: ($size / 2);
    border-left: 0;
    transform-origin: center left;
  }

  .progress-right {
    right: 0;
  }

  .progress-right .progress-bar {
    left: -100%;
    border-top-left-radius: ($size / 2);
    border-bottom-left-radius: ($size / 2);
    border-right: 0;
    transform-origin: center right;
  }

  .progress-value {
    display: flex;
    border-radius: 50%;
    font-size: 1rem;
    text-align: center;
    align-items: center; /* vertical center */
    justify-content: center; /* horizontal center */
    height: 100%;
    width: 100%;
    font-weight: 500;

    > div {
      margin: 0;
      padding: 0;
      line-height: 1.2;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    > div > span {
      margin-top: 0.15em; /* spacing between lines */
    }
  }
}

// Generate animations
@for $i from 1 through $howManySteps {
  $stepName: $i * (100 / $howManySteps);

  @if $i <= $howManySteps / 2 {
    .progress[data-percentage='#{$stepName}'] {
      .progress-right .progress-bar {
        animation: loading-#{$i} $animationTime linear forwards;
      }
      .progress-left .progress-bar {
        animation: none;
      }
    }
  }

  @if $i > $howManySteps / 2 {
    .progress[data-percentage='#{$stepName}'] {
      .progress-right .progress-bar {
        animation: loading-#{($howManySteps / 2)} $animationTime linear forwards;
      }
      .progress-left .progress-bar {
        animation: loading-#{$i - ($howManySteps / 2)} $animationTime linear forwards $animationTime;
      }
    }
  }
}

@for $i from 1 through $howManySteps / 2 {
  $degrees: 180 / ($howManySteps / 2) * $i;

  @keyframes loading-#{$i} {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(#{$degrees}deg);
    }
  }
}
.paymentForm {
  display: flex;
}
.pay_now {
  margin-bottom: 10px;
}
.monthly_fee {
  margin-left: 25px;
}
.payment-list-scroll {
  max-height: 450px;
  overflow-y: auto;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
}

.payment-list-scroll::-webkit-scrollbar {
  width: 6px;
}

.payment-list-scroll::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 255, 0.648);
  border-radius: 4px;
}

.payment-list-scroll::-webkit-scrollbar-thumb:hover {
  background-color: #909090;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.container {
  flex: 1 1 calc(50% - 15px);
  display: flex;
  gap: 10px;
  align-items: center;
  margin-bottom: 10px;
}

.icon {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50px;
  height: 50px;
  background-color: rgba(0, 64, 241, 0.087);
  border-radius: 50%;
  color: rgba(0, 0, 255, 0.648);
}

.bg-gray-50 {
  width: 400px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
}
.button-row {
  display: flex;
  justify-content: center;
  margin-bottom: 1rem;
  margin-top: -83px;
}
</style>

<route lang="yaml">
meta:
  title: ViewList
  layout: default
  subject: Auth
  active: 'viewList'
</route>
