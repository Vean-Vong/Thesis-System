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

const rental = ref({})
const customer = ref({})

// Fetch rental invoice data
const fetchRentalInvoice = async () => {
  const id = route.query.id
  if (!id) {
    alert('Missing rental ID')
    router.back()

    return
  }

  try {
    const res = await api.get(`/rentals/${id}`)
    console.log(res)
    if (res.data.status === 200) {
      rental.value = res.data.data
      customer.value = res.data.data.customer
    } else {
      alert(res.data.message || 'Rental not found')
      router.back()
    }
  } catch (error) {
    if (error.response?.status === 404) {
      alert('Rental not found.')
    } else {
      alert('Failed to load rental data.')
    }
    router.back()
  } finally {
    loading.value = false
  }
}

function formatDate(date) {
  if (!date) return 'N/A'
  const d = new Date(date)
  const day = d.getDate()
  const month = d.toLocaleString('en-US', { month: 'long' })
  const year = d.getFullYear()

  return `${day} ${month}, ${year}`
}

// Format number with 2 decimals
function formatNumber(value) {
  return Number(value || 0).toFixed(2)
}

// Navigate back
function goBack() {
  router.back()
}

// Print invoice
function printInvoice() {
  window.print()
}

// Save invoice as PDF
function saveAsPDF() {
  const element = invoiceContent.value?.$el || invoiceContent.value
  html2pdf()
    .set({
      margin: 0.5,
      filename: 'rental-invoice.pdf',
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
    })
    .from(element)
    .save()
}

// Compute remaining balance
const remainingBalance = computed(() => {
  if (!rental.value) return 0

  return rental.value.price - rental.value.paid_amount
})

onMounted(fetchRentalInvoice)
</script>

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
        <p>{{ $t('Loading rental data...') }}</p>
      </div>

      <div v-else>
        <!-- Customer & Rental Details -->
        <VRow>
          <VCol cols="6">
            <p>
              <strong>{{ $t('Customer Name') }}:</strong> {{ customer?.name || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Phone') }}:</strong> {{ customer?.phone || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Address') }}:</strong> {{ customer?.address || 'N/A' }}
            </p>
          </VCol>
          <VCol
            cols="6"
            class="text-right"
          >
            <p>
              <strong>{{ $t('Invoice No.') }}</strong
              >: {{ rental?.invoice_number }}
            </p>
            <p>
              <strong>{{ $t('Date') }}:</strong> {{ formatDate(rental?.date) }}
            </p>
            <p>
              <strong>{{ $t('Contract Type') }}:</strong> {{ rental?.contract_type }}
            </p>
          </VCol>
        </VRow>

        <VTable class="mt-6">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ $t('Product') }}</th>
              <th>{{ $t('Quantity') }}</th>
              <th>{{ $t('Price') }}</th>
              <th>{{ $t('Total') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(product, index) in rental?.products || []"
              :key="product.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ product.model }}</td>
              <td>{{ product.pivot.quantity }}</td>
              <td>${{ formatNumber(rental?.price) }}</td>
              <td>${{ formatNumber(rental?.price) }}</td>
            </tr>
          </tbody>
        </VTable>

        <VDivider />

        <div class="text-right mt-6">
          <h4 class="font-bold text-lg">{{ $t('Total Paid') }}: ${{ formatNumber(rental?.price) }}</h4>
        </div>
      </div>
    </VCard>
  </VContainer>
</template>

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
  title: rental-invoice
  layout: default
  subject: Auth
  active: 'rental-invoice'
</route>
