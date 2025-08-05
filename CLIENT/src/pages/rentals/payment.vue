<script setup>
import { reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Swal from 'sweetalert2'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-unresolved
import AppFormDetailTemplate from '@/components/AppFormDetailTemplate.vue'
import { VProgressCircular, VBtn, VIcon } from 'vuetify/lib/components/index.mjs'

const route = useRoute()
const router = useRouter()
const payments = ref([])

// Rental data
const rental = reactive({
  customer: {},
  price: 0, // Fixed rental price
  paid_amount: 0, // Total amount paid so far
  total_quantity_paid: 0, // from backend, fallback
  deposit: 0,
})

// Payment form
const payment = reactive({
  amount: 0,
  payment_date: new Date().toISOString().slice(0, 10),
  note: '',
})

// Loading states
const loading = reactive({
  fetching: false,
  saving: false,
})

// Computed quantity paid based on amount paid divided by price per rental
const totalQuantityPaid = computed(() => {
  return payments.value.reduce((sum, p) => sum + Number(p.quantity_paid || 0), 0)
})

// Fetch rental details
const fetchRental = async () => {
  const id = route.query.id
  if (!id) {
    Swal.fire('❌ Error', 'Missing rental ID.', 'error')

    return
  }

  loading.fetching = true
  try {
    const [rentalRes, paymentRes] = await Promise.all([api.get(`/rentals/${id}`), api.get(`/rentals/${id}/payments`)])

    if (rentalRes.data?.status === 200) {
      const data = rentalRes.data.data
      Object.assign(rental, {
        ...data,
        price: Number(data.price),
        paid_amount: Number(data.paid_amount || 0),
        deposit: Number(data.deposit || 0),
        total_quantity_paid: Number(data.total_quantity_paid || 0),
      })

      payments.value = paymentRes.data?.data || [] // ✅ Now this works
      payment.amount = rental.price
    } else {
      throw new Error(rentalRes.data.message || 'Failed to fetch rental.')
    }
  } catch (err) {
    console.error('❌ Fetch error:', err)
    Swal.fire('❌ Error', 'Could not load rental data.', 'error')
  } finally {
    loading.fetching = false
  }
}

// Submit payment
const submitPayment = async () => {
  if (payment.amount <= 0) {
    Swal.fire('⚠️ Invalid Amount', 'Please enter a valid payment amount.', 'warning')

    return
  }

  loading.saving = true
  try {
    const rentalId = route.query.id
    const payload = {
      amount: payment.amount,
      payment_date: payment.payment_date,
      quantity_paid: 1,
      note: payment.note,
    }

    const res = await api.post(`/rentals/${rentalId}/payments`, payload)
    console.log(res)
    if (res.data?.status === 200) {
      // Refresh rental data and reset form
      await fetchRental()
      payment.amount = rental.price
      payment.note = ''

      router.push({ name: 'rentals-paymentInvoice', query: { id: rentalId } })
    } else {
      throw new Error(res.data.message || 'Failed to record payment.')
    }
  } catch (error) {
    console.error('❌ Payment error:', error)
    Swal.fire('❌ Error', error.response?.data?.message || 'Failed to record payment.', 'error')
  } finally {
    loading.saving = false
  }
}

onMounted(fetchRental)
</script>

<template>
  <AppFormDetailTemplate :title="$t('Payment Place')">
    <div class="p-6">
      <div
        v-if="loading.fetching"
        class="text-center"
      >
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <p>{{ $t('Loading data...') }}</p>
      </div>

      <div
        v-else
        class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow"
      >
        <h2 class="text-xl font-bold mb-4 text-center">{{ $t('Rental Payment') }}</h2>

        <!-- Rental info -->
        <div class="mb-4">
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-account</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Customer Name') }}</p>
              <h2 class="name">{{ rental.customer?.name ?? '-' }}</h2>
            </div>
          </div>
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-cash</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Rental Price') }}</p>
              <h2 class="name">{{ rental.price.toLocaleString() }}</h2>
            </div>
          </div>
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-arrow-down</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Deposit') }}</p>
              <h2 class="name">{{ rental.deposit }}</h2>
            </div>
          </div>

          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-cached</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Payments Quantity') }}</p>
              <h2 class="name">{{ totalQuantityPaid }}</h2>
            </div>
          </div>

          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-arrow-up</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Already Paid') }}</p>
              <h2 class="name">{{ rental.paid_amount.toLocaleString() }}</h2>
            </div>
          </div>
        </div>

        <!-- Payment form -->
        <div>
          <VBtn
            color="primary"
            text
            class="mt-1 paymentAmount"
          >
            {{ $t('Payment Amount') }} ($ {{ payment.amount.toLocaleString() }})
          </VBtn>
          <VBtn
            color="primary"
            block
            :loading="loading.saving"
            :disabled="loading.saving"
            @click="submitPayment"
          >
            {{ $t('Pay Now') }}
          </VBtn>
        </div>
      </div>
    </div>
  </AppFormDetailTemplate>
</template>

<style>
.paymentAmount {
  margin-bottom: 10px;
}
.container {
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
</style>

<route lang="yaml">
meta:
  title: Payment
  layout: default
  subject: Auth
  active: 'rental_payment'
</route>
