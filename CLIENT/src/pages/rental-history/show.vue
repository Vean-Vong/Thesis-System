<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import html2pdf from 'html2pdf.js'
// eslint-disable-next-line import/no-unresolved
import Logo from '@/assets/images/logo.png'

const route = useRoute()
const router = useRouter()

const rental = ref(null)
const customer = ref(null)
const loading = ref(true)
const invoiceContent = ref(null)

async function fetchCustomer(id) {
  try {
    const res = await api.get(`/customers/${id}`)
    if (res.status === 200) {
      customer.value = res.data.data
    } else {
      customer.value = null
      console.warn('Customer not found')
    }
  } catch (error) {
    customer.value = null
    console.error('Failed to load customer:', error)
  }
}

async function fetchRental() {
  const rentalId = route.query.id || route.params.id
  if (!rentalId) {
    alert('No rental ID provided')
    router.back()

    return
  }
  try {
    const res = await api.get(`/rentals/${rentalId}`)
    if (res.status === 200) {
      rental.value = res.data.data
      console.log('Rental data:', rental.value)

      const customerId = rental.value?.customer_id ?? rental.value?.customerId ?? rental.value?.client_id
      if (customerId) {
        await fetchCustomer(customerId)
      } else {
        console.warn('No customer ID found in rental data')
        customer.value = null
      }
    } else {
      alert('Failed to load rental data')
      router.back()
    }
  } catch (error) {
    console.error(error)
    alert('Failed to load rental data')
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

function formatNumber(value) {
  return Number(value || 0).toFixed(2)
}

// Calculate total price by summing all product rental prices * quantity
const totalPrice = computed(() => {
  if (!rental.value?.products) return 0

  return rental.value.products.reduce((sum, product) => {
    const qty = product.pivot?.quantity || 1

    // Use pivot rental_price if available, else fallback to product price
    const price = Number(product.pivot?.rental_price || product.price) || 0

    return sum + qty * price
  }, 0)
})

// Grand total after discount
const grandTotal = computed(() => {
  const discount = Number(rental.value?.discount) || 0
  const deposit = Number(rental.value?.deposit) || 0
  const discountedTotal = totalPrice.value * (1 - discount / 100)

  return discountedTotal + deposit
})

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
      filename: 'rental-invoice.pdf',
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
    })
    .from(element)
    .save()
}

onMounted(() => {
  fetchRental()
})
</script>

<template>
  <VContainer>
    <!-- Header Buttons -->
    <VCard class="pa-5 mb-4">
      <VCardTitle class="justify-between align-center">
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
            variant="flat"
            class="ml-2"
            @click="saveAsPDF"
          >
            <VIcon start>mdi-download</VIcon>
            {{ $t('Save') }}
          </VBtn>
        </div>
      </VCardTitle>
    </VCard>

    <!-- Invoice Card -->
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
        <h1 class="text-3xl font-bold">ហ្គាន់សាន់ ខូអិលធីឌី</h1>
        <p class="text-gray-500">GANG SAN CO., LTD</p>
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
              <strong>{{ $t('Address') }}:</strong> {{ customer?.address || 'N/A' }}
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
              <strong>{{ $t('Invoice No.') }}:</strong> {{ rental?.invoice_number || 'N/A' }}
            </p>
            <p>
              <strong>{{ $t('Date') }}:</strong> {{ formatDate(rental?.date) }}
            </p>
            <p>
              <strong>{{ $t('Contract Type') }}:</strong> {{ rental?.contract_type || 'N/A' }}
            </p>
          </VCol>
        </VRow>

        <!-- Pricing Table -->
        <VTable
          class="mt-6"
          dense
        >
          <thead>
            <tr>
              <th>{{ $t('Model') }}</th>
              <th>{{ $t('Quantity') }}</th>
              <th>{{ $t('Price') }}</th>
              <th>{{ $t('Deposit') }}</th>
              <th>{{ $t('Discount') }}</th>
              <th>{{ $t('Duration') }}</th>
              <th>{{ $t('Seller') }}</th>
              <th>{{ $t('Warranty') }}</th>
              <th>{{ $t('Total') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="product in rental?.products || []"
              :key="product.id"
            >
              <td>{{ product.model || 'N/A' }}</td>
              <td>{{ product.pivot?.quantity || 1 }}</td>
              <td>${{ formatNumber(product.pivot?.rental_price || product.price) }}</td>
              <td>${{ rental?.deposit || 0 }}</td>
              <td>{{ rental?.discount || 0 }}%</td>
              <td>{{ rental?.duration }}</td>
              <td>{{ rental?.seller }}</td>
              <td>{{ rental?.warranty }}</td>
              <td>
                ${{
                  formatNumber(
                    (product.pivot?.rental_price || product.price) *
                      (product.pivot?.quantity || 1) *
                      (1 - (rental?.discount || 0) / 100),
                  )
                }}
              </td>
            </tr>
          </tbody>
        </VTable>

        <hr />

        <div class="text-right mt-6">
          <p>
            <strong>{{ $t('Sub_Total') }}:</strong> ${{ formatNumber(totalPrice) }}
          </p>
          <p>
            <strong>{{ $t('Deposit') }}:</strong> ${{ rental?.deposit || 0 }}
          </p>
          <p>
            <strong>{{ $t('Discount') }}:</strong> {{ rental?.discount || 0 }}%
          </p>
          <h4 class="font-bold text-lg">{{ $t('Grand_Total') }}: ${{ formatNumber(grandTotal) }}</h4>
        </div>
      </div>
    </VCard>
  </VContainer>
</template>

<style scoped>
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
@media print {
  button,
  .v-btn,
  .v-icon {
    display: none !important;
  }
}
.www {
  margin-top: -20px;
  margin-bottom: -1px;
}
</style>

<route lang="yaml">
meta:
  title: Rental Invoice
  layout: default
  subject: Auth
  active: 'rental_invoice'
</route>
