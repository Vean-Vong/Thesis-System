<template>
  <VContainer>
    <div class="flex justify-between items-center mb-4">
      <div class="space-x-2">
        <VBtn
          variant="text"
          class="border border-blue-300 text-blue-800"
          @click="goBack"
        >
          <VIcon start>mdi-arrow-left</VIcon>
          {{ $t('Back') }}
        </VBtn>

        <VBtn
          color="primary"
          class="print"
          variant="flat"
          @click="printInvoice"
        >
          <VIcon start>mdi-printer</VIcon>
          {{ $t('Print') }}
        </VBtn>
        <VBtn
          color="success"
          variant="flat"
          @click="saveAsPDF"
        >
          <VIcon start>mdi-content-save</VIcon>
          {{ $t('Save') }}
        </VBtn>
      </div>
    </div>

    <VCard
      id="invoiceContent"
      ref="invoiceContent"
      class="pa-6 cart_PaymentInvoice"
    >
      <div class="text-center mb-4">
        <img
          :src="Logo"
          alt="Company Logo"
          style="max-width: 100px; margin: auto"
        />
        <h1 class="text-3xl font-bold">{{ $t('ហ្គាន់សាន់ ខូអិលធីឌី') }}</h1>
        <p class="text-gray-500">{{ $t('GANG SAN CO., LTD') }}</p>
        <p class="text-gray-500 www">admin@gangsan.com.kh | www.gangsan.com.kh</p>
        <VAlert
          color="primary"
          variant="tonal"
          class="mt-4 text-white"
        >
          <h2>{{ $t('Invoice') }}</h2>
        </VAlert>
      </div>

      <div
        v-if="loading"
        class="text-center my-6"
      >
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <p>{{ $t('Loading data...') }}</p>
      </div>

      <div v-else>
        <VRow>
          <VCol cols="6">
            <p>
              <strong>{{ $t('Customer Name') }}:</strong> {{ customer?.name || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Phone') }}:</strong> {{ customer?.phone || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Job') }}:</strong> {{ customer?.job || 'N/A' }}
            </p>
          </VCol>
          <VCol
            cols="6"
            class="text-right"
          >
            <p>
              <strong>{{ $t('Invoice No.') }}</strong> | {{ payment?.id }}
            </p>
            <p>
              <strong>{{ $t('Date') }}:</strong> {{ formatDate(payment?.payment_date) }}
            </p>
            <p>
              <strong>{{ $t('Remark') }}:</strong> {{ $t('Installment') }}
            </p>
          </VCol>
        </VRow>

        <VTable class="mt-6">
          <thead>
            <tr>
              <th>{{ $t('#') }}</th>
              <th>{{ $t('Model') }}</th>
              <th>{{ $t('Price') }}</th>
              <th>{{ $t('Payments Quantity') }}</th>
              <th>{{ $t('Total') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(paymentItem, index) in [payment]"
              :key="paymentItem.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ productModel || 'N/A' }}</td>
              <td>${{ formatNumber(paymentItem.amount) }}</td>
              <td>{{ totalQuantityPaid }}/36</td>
              <td>${{ formatNumber(paymentItem.amount) }}</td>
            </tr>
          </tbody>
        </VTable>
        <VDivider />
        <div class="text-right mt-6">
          <h4 class="font-bold text-lg">{{ $t('Total Paid') }}: ${{ formatNumber(payment?.amount) }}</h4>
        </div>
        <div class="text-right mt-6">
          <h4 class="font-bold text-lg">{{ $t('Remaining Balance') }}: ${{ formatNumber(remainingBalance) }}</h4>
        </div>
      </div>
    </VCard>
  </VContainer>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import html2pdf from 'html2pdf.js'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-unresolved
import Logo from '@/assets/images/logo.png'
import { VDivider } from 'vuetify/lib/components/index.mjs'

const route = useRoute()
const router = useRouter()
const loading = ref(true)
const invoiceContent = ref(null)

const payment = ref({})
const installment = ref({})
const customer = ref({})
const productModel = ref('')
const payments = ref([]) // <-- payments array to hold all payments of installment

async function fetchSaleProducts(saleId) {
  try {
    const res = await api.get(`/sales/${saleId}`)
    if (res.data?.status === 200) {
      const sale = res.data.data
      installment.value.sale = sale

      const products = sale.products || []
      if (products.length > 0) {
        productModel.value = products[0].model || 'N/A'
      }
    } else {
      installment.value.sale = { products: [] }
    }
  } catch (err) {
    console.error('❌ Failed to fetch sale/products:', err)
    installment.value.sale = { products: [] }
  }
}

async function fetchPayments(installmentId) {
  try {
    const res = await api.get(`/installments/${installmentId}/payments`)
    if (res.data?.status === 200) {
      payments.value = res.data.data || []
    } else {
      payments.value = []
    }
  } catch (err) {
    console.error('❌ Failed to fetch payments:', err)
    payments.value = []
  }
}

async function fetchPaymentInvoice() {
  const id = route.query.id
  if (!id) {
    alert('Missing payment ID')
    router.back()

    return
  }

  try {
    const res = await api.get(`/payments/${id}/invoice`)
    if (res.data.status === 200) {
      payment.value = res.data.data.payment
      customer.value = res.data.data.customer
      const installmentData = res.data.data.installment || {}
      installment.value = {
        ...installmentData,
        sale: { products: [] },
      }
      if (installmentData.sale_id) {
        await fetchSaleProducts(installmentData.sale_id)
      }
      if (installmentData.id) {
        await fetchPayments(installmentData.id) // fetch all payments for this installment
      }
    } else {
      alert('Failed to load payment invoice')
      router.back()
    }
  } catch (err) {
    console.error(err)
    alert('Error fetching payment invoice')
    router.back()
  } finally {
    loading.value = false
  }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString()
}

function formatNumber(value) {
  return Number(value || 0).toFixed(2)
}

function goBack() {
  router.back()
}

function printInvoice() {
  window.print()
}

function saveAsPDF() {
  const element = invoiceContent.value?.$el || invoiceContent.value
  html2pdf()
    .set({
      margin: 0.5,
      filename: 'payment-invoice.pdf',
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
    })
    .from(element)
    .save()
}

const installPrice = computed(() => {
  const products = installment.value.sale?.products || []
  if (!products.length) return 0

  return Number(products[0].install_price) || 0
})

const duration = computed(() => {
  return Number(installment.value.sale?.duration) || 36
})

const totalQuantityPaid = computed(() => {
  return payments.value.reduce((sum, p) => sum + Number(p.quantity_paid || 0), 0)
})

const remainingBalance = computed(() => {
  const totalPrice = installPrice.value * duration.value
  const paid = Number(installment.value.paid_amount || 0)
  const remaining = totalPrice - paid

  return remaining > 0 ? remaining : 0
})

onMounted(fetchPaymentInvoice)
</script>

<style>
.print {
  margin-right: 10px;
  margin-left: 10px;
}
.www {
  margin-top: -20px;
  margin-bottom: -1px;
}
@media print {
  body * {
    visibility: hidden;
  }
  #invoiceContent,
  #invoiceContent * {
    visibility: visible;
  }
  #invoiceContent {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
  }
  .v-btn,
  .print,
  .border {
    display: none !important;
  }
}
</style>

<route lang="yaml">
meta:
  title: payment-invoice
  layout: default
  subject: Auth
  active: 'payment-invoice'
</route>
