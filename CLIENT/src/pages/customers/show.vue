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

const fetchCustomer = async () => {
  try {
    const res = await api.get(`/customers/${route.query.id}`)
    if (res.data.status === 200) {
      const data = res.data.data

      // Format customer date
      data.date = formatDate(data.date)

      // Format sales dates
      if (Array.isArray(data.sales)) {
        data.sales = data.sales.map(sale => ({
          ...sale,
        }))
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
    <!-- Loading state -->
    <VCard v-if="loading">
      <VCardText>
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <span class="ml-3">{{ $t('Loading data...') }}</span>
      </VCardText>
    </VCard>

    <!-- Error state -->
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

    <!-- Customer & Sales Data -->
    <VCard v-else>
      <VCardText>
        <VRow dense>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Name') }}:</strong> {{ customer.name || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Address') }}:</strong> {{ customer.address || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Phone') }}:</strong> {{ customer.phone || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Job') }}:</strong> {{ customer.job || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Date') }}:</strong> {{ customer.date || '-' }}
          </VCol>
        </VRow>

        <VDivider class="my-4" />

        <h3>{{ $t('Product') }}</h3>

        <VList
          v-if="customer.sales && customer.sales.length"
          dense
        >
          <VListItem
            v-for="sale in customer.sales"
            :key="sale.id"
          >
            <VListItemContent>
              <VListItemTitle> {{ $t('Model') }}: {{ sale.model }} </VListItemTitle>
              <VListItemTitle>{{ $t('Price') }}: ${{ sale.price }}</VListItemTitle>
              <VListItemTitle>{{ $t('Discount') }}: {{ sale.discount }}%</VListItemTitle>
              <VListItemTitle>{{ $t('Sub_Total') }}: {{ sale.sub_total }}</VListItemTitle>
              <VListItemTitle>{{ $t('Deposit') }}: ${{ sale.deposit }}</VListItemTitle>
              <VListItemTitle>{{ $t('Duration') }}: {{ sale.duration }}</VListItemTitle>
              <VListItemTitle>{{ $t('Contract Type') }}: {{ sale.contract_type }}</VListItemTitle>
              <VListItemTitle>{{ $t('Seller') }}: {{ sale.seller }}</VListItemTitle>
              <VListItemTitle>{{ $t('Warranty') }}: {{ sale.warranty }}</VListItemTitle>
            </VListItemContent>
          </VListItem>
        </VList>

        <p v-else>{{ $t('No sales found for this customer.') }}</p>
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>
