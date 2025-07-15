<template>
  <VContainer>
    <!-- Header -->
    <VCard class="pa-5 mb-4">
      <VCardTitle class="justify-between align-center">
        <div class="text-center">
          <h1 class="text-2xl font-bold">{{ $t('ហ្គាន់សាន់ ខូអិលធីឌី') }}</h1>
          <p class="text-gray-500">{{ $t('Gang San Co,Ltd') }}</p>
        </div>
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
            class="ml-2"
            @click="printInvoice"
          >
            <VIcon start>mdi-printer</VIcon>
            {{ $t('Print') }}
          </VBtn>
          <VBtn
            color="success"
            class="ml-2"
            variant="flat"
            @click="saveAsPDF"
          >
            <VIcon start>mdi-content-save</VIcon>
            {{ $t('Save') }}
          </VBtn>
        </div>
      </VCardTitle>
    </VCard>

    <!-- Invoice Content -->
    <VCard
      ref="invoiceContent"
      class="pa-6"
    >
      <!-- 🛠 Added ref here -->
      <VRow>
        <VCol
          cols="12"
          class="text-center"
        >
          <VAlert
            color="primary"
            variant="elevated"
            class="text-center text-white"
          >
            <h2 class="text-white">{{ $t('Invoice') }}</h2>
          </VAlert>
        </VCol>
        <div class="Title">
          <div class="text-center">
            <h2 class="font-bold">{{ $t('ហ្គាន់សាន់ ខូអិលធីឌី') }}</h2>
            <p class="text-gray-500">{{ $t('Gang San Co,Ltd') }}</p>
          </div>
          <h2 class="">{{ $t('Invoice') }}</h2>
        </div>
      </VRow>

      <VRow>
        <VCol cols="6">
          <h4 class="font-bold">{{ $t('Bill To:') }}</h4>
          <p>{{ $t('Customer Name') }}: {{ sale?.customer?.name }}</p>
          <p>{{ $t('Phone') }}: {{ sale?.customer?.phone }}</p>
          <p>{{ $t('Address') }}: {{ sale?.customer?.address }}</p>
        </VCol>
        <VCol
          cols="6"
          class="text-right"
        >
          <h4 class="font-bold">{{ $t('Invoice Details:') }}</h4>
          <p>Invoice #: 001</p>
          <p>{{ $t('Date') }}: {{ sale?.date }}</p>
          <p>{{ $t('Status') }}: <span class="text-success">Paid</span></p>
        </VCol>
      </VRow>

      <!-- Items Table -->
      <VTable class="mt-6">
        <thead>
          <tr>
            <th>{{ $t('Model') }}</th>
            <th>{{ $t('Quantity') }}</th>
            <th>{{ $t('Deposit') }}</th>
            <th>{{ $t('Price') }}</th>
            <th>{{ $t('Discount') }}</th>
            <th>{{ $t('Duration') }}</th>
            <th>{{ $t('Seller') }}</th>
            <th>{{ $t('Contract Type') }}</th>
            <th>{{ $t('Sub_Total') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ sale?.item?.model || 'N/A' }}</td>
            <td>{{ sale?.item?.quantity }}</td>
            <td>{{ sale?.deposit }}</td>
            <td>${{ sale?.item?.price }}</td>
            <td>{{ sale?.item?.discount }}%</td>
            <td>{{ sale?.duration }}</td>
            <td>{{ sale?.seller }}</td>
            <td>{{ sale?.contract_type }}</td>
            <td>${{ sale?.item?.sub_total }}</td>
          </tr>
        </tbody>
      </VTable>

      <!-- Total -->
      <VRow class="justify-end mt-4">
        <VCol cols="4">
          <div class="text-right">
            <p>
              <strong>{{ $t('Price') }}</strong> : ${{ sale?.item?.price || '0.00' }}
            </p>
            <p>
              <strong>{{ $t('Discount') }}</strong> : {{ sale?.item?.discount || 0 }}%
            </p>
            <h4 class="text-xl font-bold">{{ $t('Sub_Total') }} : ${{ sale?.item?.sub_total || '0.00' }}</h4>
          </div>
        </VCol>
      </VRow>
    </VCard>
  </VContainer>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import html2pdf from 'html2pdf.js'

const route = useRoute()
const router = useRouter()
const invoiceContent = ref(null)

const sale = ref(null)
const loading = ref(true)

async function fetchSale() {
  const saleId = route.query.id

  if (!saleId) {
    Swal.fire('Error', 'No sale ID provided', 'error')
    router.back()

    return
  }
  try {
    const res = await api.get(`/sales/${saleId}`)

    if (res.status === 200) {
      sale.value = res.data.data
    } else {
      Swal.fire('Error', 'Failed to load sale data', 'error')
      router.back()
    }
  } catch (err) {
    console.error(err)
    Swal.fire('Error', 'Failed to load sale data', 'error')
    router.back()
  } finally {
    loading.value = false
  }
}

function printInvoice() {
  window.print()
}

function saveAsPDF() {
  const element = invoiceContent.value.$el || invoiceContent.value
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

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0') // Months start at 0
  const year = date.getFullYear()

  return `ថ្ងៃ ${day} ខែ ${month} ឆ្នាំ ${year}`
}

function goBack() {
  router.back()
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
.Title {
  display: flex;
  justify-content: space-between;
  align-items: center; /* vertically align items */
  width: 100%; /* ensure full width */
}
</style>

<route lang="yaml">
meta:
  title: Invoice
  layout: default
  subject: Auth
  active: 'pos.invoice'
</route>
