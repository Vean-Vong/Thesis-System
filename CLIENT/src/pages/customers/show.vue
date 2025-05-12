<!-- eslint-disable import/no-unresolved -->
<!-- eslint-disable import/extensions -->
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/utilites'

const route = useRoute()
const loading = ref(true)
const customer = ref(null)
const error = ref(false)

const formatDate = date => {
  if (!date) return '-'
  const d = new Date(date)
  const day = d.getDate()
  const month = d.toLocaleString('en-US', { month: 'long' })
  const year = d.getFullYear()
  // eslint-disable-next-line newline-before-return
  return `${day} ${month}, ${year}`
}

const fetchCustomer = async () => {
  try {
    const res = await api.get(`/customers/${route.query.id}`)
    const data = res.data.data
    data.date = formatDate(data.date)
    customer.value = data
  } catch (e) {
    console.error('Failed to fetch customer:', e)
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
    <!-- When Loading -->
    <VCard v-if="loading">
      <VCardText>
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <span class="ml-3">{{ $t('Loading data...') }}</span>
      </VCardText>
    </VCard>

    <!-- When Error -->
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

    <!-- When Data is Loaded -->
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
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: Customer View
  layout: default
  subject: Auth
  active: 'customer'
</route>
