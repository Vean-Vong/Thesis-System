<!-- eslint-disable import/no-unresolved -->
<script setup>
import { ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
import { useRoute } from 'vue-router'

const route = useRoute()
const loading = ref(true)
const customer = ref(null)

const fetchCustomer = async () => {
  try {
    const res = await api.get(`/customers/${route.query.id}`)
    customer.value = res.data.data
  } catch (error) {
    console.error('Failed to fetch customer:', error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchCustomer)
</script>

<template>
  <AppFormCreateTemplate
    cols="6"
    :title="$t('Customer Details')"
  >
    <VCard v-if="!loading">
      <VCardText>
        <VRow dense>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Name') }}:</strong> {{ customer?.name || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Address') }}:</strong> {{ customer?.address || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Phone') }}:</strong> {{ customer?.phone || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Email') }}:</strong> {{ customer?.email || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Job') }}:</strong> {{ customer?.job || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Date') }}:</strong> {{ customer?.date || '-' }}
          </VCol>
        </VRow>
      </VCardText>
    </VCard>

    <VCard v-else>
      <VCardText>
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <span class="ml-3">{{ $t('Loading customer data...') }}</span>
      </VCardText>
    </VCard>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Customer View
  layout: default
  subject: Auth
  active: 'customer'
</route>
