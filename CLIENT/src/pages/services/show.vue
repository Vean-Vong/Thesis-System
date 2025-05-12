<!-- eslint-disable import/no-unresolved -->
<!-- eslint-disable import/extensions -->
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import constant from '@/constants'

const route = useRoute()
const loading = ref(true)
const service = ref(null)
const error = ref(false)
const fetchService = async () => {
  try {
    const res = await api.get(`/services/${route.query.id}`)
    const data = res.data.data
    service.value = data
  } catch (e) {
    console.error('Failed to fetch Service:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchService)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Service Details')"
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
          {{ $t('Unable to load Service details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: Service Details -->
          <VCol
            cols="12"
            md="8"
          >
            <VRow dense>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Model') }}:</strong>
                <span class="text-subtitle-1"> {{ service.model || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Price') }}:</strong>
                <span class="text-subtitle-1"> {{ service.price || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Date') }}:</strong>
                <span class="text-subtitle-1"> {{ service.date || '-' }} </span>
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Duration') }}:</strong>
                <span class="text-subtitle-1"> {{ service.duration || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Warranty') }}:</strong>
                <span class="text-subtitle-1"> {{ service.warranty || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Seller') }}:</strong>
                <span class="text-subtitle-1"> {{ service.seller || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Contract Type') }}:</strong>
                <span class="text-subtitle-1"> {{ service.contract_type || '-' }} </span>
              </VCol>
            </VRow>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: Service View
  layout: default
  subject: Auth
  active: 'Service'
</route>
