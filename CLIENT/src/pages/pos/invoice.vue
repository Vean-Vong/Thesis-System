<template>
  <VContainer>
    <!-- Header Buttons -->
    <div class="flex justify-between items-center mb-4">
      <div></div>
      <div class="space-x-2">
        <VBtn
          color="error"
          variant="flat"
          @click="goBack"
        >
          <VIcon start>mdi-arrow-left</VIcon>
          {{ $t('Back') }}
        </VBtn>
        <VBtn
          color="primary"
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
          {{ $t('Save as PDF') }}
        </VBtn>
      </div>
    </div>

    <!-- Invoice Card -->
    <VCard
      class="pa-6"
      ref="invoiceContent"
    >
      <!-- Title -->
      <div class="text-center mb-4">
        <h1 class="text-3xl font-bold">{{ $t('ហ្គាន់សាន់ ខូអិលធីឌី') }}</h1>
        <p class="text-gray-500">{{ $t('Gang San Co., Ltd') }}</p>
        <VAlert
          color="primary"
          variant="tonal"
          class="mt-4 text-white"
        >
          <h2 class="text-">{{ $t('Invoice') }}</h2>
        </VAlert>
      </div>

      <!-- Loading -->
      <div
        v-if="loading"
        class="text-center my-6"
      >
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <p>{{ $t('Loading...') }}</p>
      </div>

      <!-- Invoice content -->
      <div v-else>
        <!-- Customer & Sale Details -->
        <VRow>
          <VCol cols="6">
            <!-- <h4 class="font-bold mb-2">{{ $t('Customer Information') }}</h4> -->
            <p>
              <strong>{{ $t('Name') }}:</strong> {{ sale.customer?.name || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Phone') }}:</strong> {{ sale.customer?.phone || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Address') }}:</strong> {{ sale.customer?.address || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Job') }}:</strong> {{ sale.customer?.job || 'N/A' }}
            </p>
          </VCol>
          <VCol
            cols="6"
            class="text-right"
          >
            <!-- <h4 class="font-bold mb-2">{{ $t('Sale Information') }}</h4> -->
            <p>Invoice No. 001</p>
            <p>
              <strong>{{ $t('Date') }}:</strong> {{ sale.date }}
            </p>
            <p>{{ $t('Status') }}: <span class="text-success">Paid</span></p>
          </VCol>
        </VRow>

        <!-- Products Table -->
        <VTable class="mt-6">
          <thead>
            <tr>
              <th>{{ $t('Model') }}</th>
              <th>{{ $t('Quantity') }}</th>
              <th>{{ $t('Deposit') }}</th>
              <th>{{ $t('Price') }}</th>
              <th>{{ $t('Discount') }}</th>
              <th>{{ $t('Seller') }}</th>
              <th>{{ $t('Contract Type') }}</th>
              <th>{{ $t('Sub_Total') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="product in sale.products || []"
              :key="product.id"
            >
              <td>{{ product.model || 'N/A' }}</td>
              <td>{{ product.pivot?.quantity || 0 }}</td>
              <td>${{ formatNumber(sale.deposit) }}</td>
              <td>${{ formatNumber(product.pivot?.price) }}</td>
              <td>{{ sale.discount || 0 }}%</td>
              <td>{{ sale.seller || 0 }}</td>
              <td>{{ sale.contract_type || 0 }}</td>
              <td>${{ calculateSubTotal(product.pivot?.price, product.pivot?.quantity, sale.discount) }}</td>
            </tr>
          </tbody>
        </VTable>

        <!-- Total Section -->
        <div class="text-right mt-6">
          <p>
            <strong>{{ $t('Total Price') }}:</strong>
            ${{ formatNumber(totalPrice) }}
          </p>
          <p>
            <strong>{{ $t('Total Discount') }}:</strong>
            {{ sale.discount || 0 }}%
          </p>
          <h4 class="font-bold text-lg">{{ $t('Grand Total') }}: ${{ formatNumber(grandTotal) }}</h4>
        </div>
      </div>
    </VCard>
  </VContainer>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import html2pdf from 'html2pdf.js'

const route = useRoute()
const router = useRouter()
const sale = ref(null)
const loading = ref(true)
const invoiceContent = ref(null)

async function fetchSale() {
  const saleId = route.query.id
  if (!saleId) {
    alert('No sale ID provided')
    router.back()

    return
  }
  try {
    const res = await api.get(`/sales/${saleId}`)
    if (res.status === 200) {
      sale.value = res.data.data
    } else {
      alert('Failed to load sale data')
      router.back()
    }
  } catch (error) {
    console.error(error)
    alert('Failed to load sale data')
    router.back()
  } finally {
    loading.value = false
  }
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)

  return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`
}

function formatNumber(value) {
  return Number(value || 0).toFixed(2)
}

const totalPrice = computed(() => {
  if (!sale.value?.products) return 0

  return sale.value.products.reduce((sum, product) => {
    const price = Number(product.pivot?.price) || 0
    const quantity = Number(product.pivot?.quantity) || 0

    return sum + price * quantity
  }, 0)
})

const grandTotal = computed(() => {
  const discount = Number(sale.value?.discount) || 0

  return totalPrice.value - (totalPrice.value * discount) / 100
})

function calculateSubTotal(price, quantity, discount) {
  const subtotal = (Number(price) || 0) * (Number(quantity) || 0)
  const discounted = subtotal - (subtotal * (Number(discount) || 0)) / 100

  return discounted.toFixed(2)
}

function goBack() {
  router.back()
}

function printInvoice() {
  window.print()
}

function saveAsPDF() {
  const element = invoiceContent.value?.$el || invoiceContent.value
  if (!element) return
  html2pdf()
    .set({
      margin: 0.5,
      filename: 'invoice.pdf',
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
    })
    .from(element)
    .save()
}

onMounted(() => {
  fetchSale()
})
</script>

<style scoped>
@media print {
  button,
  .v-btn,
  .v-icon {
    display: none !important;
  }
}
</style>

<route lang="yaml">
meta:
  title: Invoice
  layout: default
  subject: Auth
  active: 'pos.invoice'
</route>
