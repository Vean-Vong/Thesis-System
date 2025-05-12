<!-- eslint-disable import/no-unresolved -->
<!-- eslint-disable import/extensions -->
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/utilites'

const route = useRoute()
const loading = ref(true)
const sale = ref(null)
const error = ref(false)
const fetchSale = async () => {
  try {
    const res = await api.get(`/sales/${route.query.id}`)
    const data = res.data.data
    sale.value = data
  } catch (e) {
    console.error('Failed to fetch sale:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchSale)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Sale Details')"
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
          {{ $t('Unable to load sale details.') }}
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
            <strong>{{ $t('Model') }}:</strong> {{ sale.model || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Price') }}:</strong> {{ sale.price || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Discount') }}:</strong> {{ sale.discount || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Date') }}:</strong> {{ sale.date || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Duration') }}:</strong> {{ sale.duration || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Warranty') }}:</strong> {{ sale.warranty || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Seller') }}:</strong> {{ sale.seller || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Contract Type') }}:</strong> {{ sale.contract_type || '-' }}
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: sale View
  layout: default
  subject: Auth
  active: 'sale'
</route>
