<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import Swal from 'sweetalert2'

const route = useRoute()
const loading = ref(true)
const error = ref(false)
const customer = ref(null)

const formatDate = date => {
  if (!date) return '-'
  const d = new Date(date)
  const day = d.getDate()
  const month = d.toLocaleString('en-US', { month: 'long' })
  const year = d.getFullYear()

  return `${day} ${month}, ${year}`
}

const formatNumber = num => {
  if (typeof num !== 'number') num = Number(num) || 0

  return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// eslint-disable-next-line sonarjs/cognitive-complexity
const fetchCustomer = async () => {
  try {
    const res = await api.get(`/customers/${route.query.id}`)
    if (res.data.status === 200) {
      const data = res.data.data

      // Format customer date
      data.date = formatDate(data.date)

      // Format sales
      if (Array.isArray(data.sales)) {
        data.sales = data.sales.map(sale => {
          const price = Number(sale.price) || 0
          const quantity = Number(sale.quantity) || 1
          const discount = Number(sale.discount) || 0
          const deposit = Number(sale.deposit) || 0

          const totalBeforeDiscount = price * quantity
          const discountAmount = (totalBeforeDiscount * discount) / 100
          const totalAfterDiscount = totalBeforeDiscount - discountAmount
          const sub_total = totalAfterDiscount - deposit

          return {
            ...sale,
            sub_total: sub_total > 0 ? formatNumber(sub_total) : '0.00',
          }
        })
      }

      // Format rentals similarly (if any)
      if (Array.isArray(data.rentals)) {
        data.rentals = data.rentals.map(rental => {
          const price = Number(rental.price) || 0
          const deposit = Number(rental.deposit) || 0

          const sub_total = price - deposit

          return {
            ...rental,
            sub_total: sub_total > 0 ? formatNumber(sub_total) : '0.00',
          }
        })
      }

      customer.value = data
    } else {
      throw new Error('Failed to load customer data')
    }
  } catch (err) {
    console.error('Error fetching customer:', err)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchCustomer)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Customer Details')"
  >
    <!-- Loading -->
    <VCard v-if="loading">
      <VCardText>
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <span class="ml-3">{{ $t('Loading data...') }}</span>
      </VCardText>
    </VCard>

    <!-- Error -->
    <VCard v-else-if="error">
      <VCardText>
        <VAlert
          type="error"
          color="error"
        >
          {{ $t('Unable to load customer details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Customer and Sales / Rentals -->
    <VCard
      v-else
      class="border border-gray-200 shadow-sm rounded-xl"
    >
      <VCardText>
        <!-- Customer info -->
        <div class="bg-gradient-to-r from-indigo-100 to-blue-50 p-4 rounded-xl mb-4">
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-account</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Customer Name') }}</p>
              <h2 class="name">{{ customer.name || '-' }}</h2>
            </div>
          </div>
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-phone</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Phone') }}</p>
              <h2 class="name">{{ customer.phone || '-' }}</h2>
            </div>
          </div>
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-map-marker</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Address') }}</p>
              <h2 class="name">{{ customer.address || '-' }}</h2>
            </div>
          </div>
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-briefcase</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Job') }}</p>
              <h2 class="name">{{ customer.job || '-' }}</h2>
            </div>
          </div>
          <div class="container">
            <div class="icon"><VIcon color="indigo">mdi-calendar</VIcon></div>
            <div class="title">
              <p class="names">{{ $t('Date') }}</p>
              <h2 class="name">{{ customer.date || '-' }}</h2>
            </div>
          </div>
        </div>

        <VDivider class="my-3" />

        <!-- Show Sales only if exist -->
        <template v-if="customer.sales && customer.sales.length">
          <h3 class="text-lg font-bold text-blue-700 mb-2">🛒 {{ $t('Sales Products') }}</h3>

          <VExpansionPanels
            focusable
            inset
          >
            <VExpansionPanel
              v-for="sale in customer.sales"
              :key="sale.id"
              class="border rounded-lg mb-2"
            >
              <VExpansionPanelTitle>
                <div class="flex justify-between w-full">
                  <span
                    >{{ $t('Model') }}: <strong>{{ sale.model }}</strong></span
                  >
                  | {{ $t('Price') }}: <span class="text-green-600">${{ formatNumber(sale.price) }}</span>
                </div>
              </VExpansionPanelTitle>
              <VExpansionPanelText>
                <VRow dense>
                  <VCol cols="6"
                    ><strong>{{ $t('Discount') }}:</strong> {{ sale.discount }}%</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Sub_Total') }}:</strong> ${{ sale.sub_total }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Deposit') }}:</strong> ${{ formatNumber(sale.deposit) }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Duration') }}:</strong> {{ sale.duration }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Contract Type') }}:</strong> {{ sale.contract_type }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Seller') }}:</strong> {{ sale.seller }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Warranty') }}:</strong> {{ sale.warranty }}</VCol
                  >
                </VRow>
              </VExpansionPanelText>
            </VExpansionPanel>
          </VExpansionPanels>
        </template>

        <!-- Show Rentals if no sales exist -->
        <template v-else-if="customer.rentals && customer.rentals.length">
          <h3 class="text-lg font-bold text-blue-700 mb-2">🛒 {{ $t('Rental Products') }}</h3>

          <VExpansionPanels
            focusable
            inset
          >
            <VExpansionPanel
              v-for="rental in customer.rentals"
              :key="rental.id"
              class="border rounded-lg mb-2"
            >
              <VExpansionPanelTitle>
                <div class="flex justify-between w-full">
                  <span
                    >{{ $t('Model') }}: <strong>{{ rental.model }}</strong></span
                  >
                  | {{ $t('Price') }}: <span class="text-green-600">${{ formatNumber(rental.price) }}</span>
                </div>
              </VExpansionPanelTitle>
              <VExpansionPanelText>
                <VRow dense>
                  <VCol cols="6"
                    ><strong>{{ $t('Deposit') }}:</strong> ${{ formatNumber(rental.deposit) }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Sub_Total') }}:</strong> ${{ rental.sub_total }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Duration') }}:</strong> {{ rental.duration }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Contract Type') }}:</strong> {{ rental.contract_type }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Seller') }}:</strong> {{ rental.seller }}</VCol
                  >
                  <VCol cols="6"
                    ><strong>{{ $t('Warranty') }}:</strong> {{ rental.warranty }}</VCol
                  >
                </VRow>
              </VExpansionPanelText>
            </VExpansionPanel>
          </VExpansionPanels>
        </template>

        <!-- Show "no data" if neither sales nor rentals -->
        <template v-else>
          <p class="text-gray-500 italic mt-3 text-center">
            {{ $t('No data available') }}
          </p>
        </template>
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: Customer Detail
  layout: default
  active: 'customer'
</route>
