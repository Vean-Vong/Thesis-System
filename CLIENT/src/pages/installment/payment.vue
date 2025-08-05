<script setup>
import { reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Swal from 'sweetalert2'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'

const route = useRoute()
const router = useRouter()

// Installment data
const installment = reactive({
  customer: {},
  total_price: 0,
  deposit: 0,
  paid_amount: 0,
  monthly_fee: 0,
  status: '',
  due_date: '',
})

// Payment form
const payment = reactive({
  amount: 0,
  payment_date: new Date().toISOString().slice(0, 10),
  note: '',
  method: '',
  reference: '',
  payer_name: '',
})

// Loading state
const loading = reactive({
  fetching: false,
  saving: false,
})

// Error message (optional)
const error = reactive({
  message: '',
})

// Computed fields
const totalPrice = computed(() => Number(installment.total_price) || 0)
const deposit = computed(() => Number(installment.deposit) || 0)
const paidAmount = computed(() => Number(installment.paid_amount) || 0)

const totalPaid = computed(() => {
  return Number(installment.paid_amount) + Number(payment.amount)
})

const remainingBalance = computed(() => {
  const products = installment.value.sale?.products || []
  const installPrice = products.length > 0 ? Number(products[0].install_price || 0) : 0
  const duration = 36
  const paid = Number(installment.value.paid_amount || 0)

  return installPrice * duration - paid
})

const paidPercentage = computed(() => {
  if (totalPrice.value === 0) return 0

  return Math.min((totalPaid.value / totalPrice.value) * 100, 100)
})

const isFullyPaid = computed(() => installment.status === 'paid' || balance.value <= 0)

const isOnTime = computed(() => {
  if (!installment.due_date) return true
  const due = new Date(installment.due_date)
  const paymentDate = new Date(payment.payment_date)

  return paymentDate <= due
})

// 👉 Approximate the quantity paid (payments count)
const qttPaid = computed(() => {
  const monthly = Number(installment.monthly_fee)
  const paid = Number(installment.paid_amount)

  if (monthly <= 0) return 0 // avoid divide by zero

  return Math.floor(paid / monthly) // approximate number of payments made
})

// Fetch installment
const fetchInstallment = async () => {
  const id = route.query.id
  if (!id) {
    Swal.fire('❌ Error', 'Missing installment ID.', 'error')
    error.message = 'Installment ID missing in route.'

    return
  }

  loading.fetching = true
  try {
    const res = await api.get(`/installments/${id}`)
    console.log('Installment response:', res.data.data)

    if (res.data?.status === 200) {
      z
      Object.assign(installment, {
        ...res.data.data,
        total_price: Number(res.data.data.total_price),
        deposit: Number(res.data.data.deposit),
        paid_amount: Number(res.data.data.paid_amount),
        status: res.data.data.status,
        due_date: res.data.data.due_date,
        monthly_fee: Number(res.data.data.monthly_fee),
      })

      // 👉 Set default payment amount to monthly fee
      if (!isFullyPaid.value && installment.monthly_fee > 0) {
        payment.amount = installment.monthly_fee
      }
    } else {
      throw new Error(res.data.message || 'Failed to fetch installment.')
    }
  } catch (err) {
    console.error('❌ Fetch error:', err)
    Swal.fire('❌ Error', 'Could not load installment data.', 'error')
    error.message = 'Failed to fetch installment data.'
  } finally {
    loading.fetching = false
  }
}

// Submit payment
const submitPayment = async () => {
  if (isFullyPaid.value) {
    Swal.fire('✅ Fully Paid', 'This installment is already fully paid.', 'info')

    return
  }

  loading.saving = true
  try {
    const payload = {
      ...payment,
      installment_id: route.query.id,
    }

    const res = await api.post(`/installments/${route.query.id}/payments`, payload)

    if (res.data?.status === 200) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Payment recorded successfully.',
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

    // No need to call fetchInstallment or reset form since we’re navigating away
  } catch (error) {
    Swal.fire('❌ Error', error.response?.data?.message || 'Failed to record payment.', 'error')
  } finally {
    loading.saving = false
  }
}

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
}

onMounted(fetchInstallment)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Payment')"
  >
    <div class="min-h-screen bg-gradient-to-r from-indigo-50 to-blue-50 flex justify-center items-center p-6">
      <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md border border-gray-200">
        <!-- Loading spinner -->
        <VCard v-if="loading.fetching">
          <VCardText>
            <VProgressCircular
              indeterminate
              color="primary"
            />
            <span class="ml-3">{{ $t('Loading data...') }}</span>
          </VCardText>
        </VCard>

        <div v-else>
          <!-- Circular progress -->
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
                  <!-- <span class="text-sm">បានបង់</span> -->
                </div>
              </div>
            </div>
          </div>

          <!-- Installment details -->
          <div class="bg-gray-50 rounded-lg p-3 mb-4 space-y-1 text-sm ps-5">
            <p>
              <span class="font-medium text-gray-700">{{ $t('Customer Name') }}: </span>
              {{ installment.customer?.name || '-' }}
            </p>
            <p>
              <span class="font-medium text-gray-700">{{ $t('Deposit') }}: </span>
              <span class="text-green-600 font-semibold">${{ deposit.toLocaleString() }}</span>
            </p>
            <p>
              <span class="font-medium text-gray-700">{{ $t('Already Paid') }}: </span>
              <span class="text-green-600 font-semibold">${{ paidAmount.toLocaleString() }}</span>
            </p>
            <p>
              <span class="font-medium text-gray-700">{{ $t('Remaining Balance') }}: </span>
              <span class="text-red-600 font-semibold">${{ remainingBalance.toFixed(2) }}</span>
            </p>
            <p>{{ $t('Payments Quantity') }}: {{ qttPaid }}/36</p>
          </div>

          <p
            v-if="isFullyPaid"
            class="text-green-600 font-bold mt-2 flex items-center justify-center"
          >
            🔒 Fully Paid
          </p>
          <p
            v-else-if="isOnTime"
            class="text-blue-600 mt-2"
          >
            🕒 Payment is on time
          </p>
          <p
            v-else
            class="text-red-600 mt-2"
          >
            ⚠️ Payment overdue
          </p>

          <!-- Payment form -->
          <VRow
            dense
            v-if="!isFullyPaid"
          >
            <VBtn
              color="primary"
              variant="text"
              text
              class="mt-1 monthly_fee border border-blue-300 text-blue-800"
              @click="payment.amount = installment.monthly_fee"
            >
              {{ $t('Payment Amount') }} ($ {{ installment.monthly_fee.toLocaleString() }})
            </VBtn>

            <VCol
              cols="12"
              md="4"
            >
              <div class="border rounded px-3 py-2 bg-primary text-gray-800">
                <span class="font-medium">{{ $t('Date') }}:</span>
                {{ payment.payment_date }}
              </div>
            </VCol>

            <VCol
              cols="12"
              class="pay_now"
            >
              <VBtn
                color="primary"
                block
                :loading="loading.saving"
                :disabled="loading.saving"
                @click="submitPayment"
              >
                {{ $t('Pay Now') }}
              </VBtn>
            </VCol>
          </VRow>
        </div>
      </div>
    </div>
  </AppFormDetailTemplate>
</template>

<style lang="scss" scoped>
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
.pay_now {
  padding: 20px;
}
.monthly_fee {
  margin-left: 25px;
}
</style>

<route lang="yaml">
meta:
  title: Payment
  layout: default
  subject: Auth
  active: 'payment'
</route>
