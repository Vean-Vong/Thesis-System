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
const setup = ref(null)
const error = ref(false)
const fetchSetup = async () => {
  try {
    const res = await api.get(`/setup/${route.query.id}`)
    const data = res.data.data
    setup.value = data
  } catch (e) {
    console.error('Failed to fetch setup:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchSetup)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Setup Details')"
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
          {{ $t('Unable to load setup details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: setup Details -->
          <VCol
            cols="12"
            md="8"
          >
            <VRow dense>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Customer Name') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.customer_name || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Model') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.model || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Invoice Number') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.invoice_number || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Unit') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.unit || '-' }} </span>
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cash on Hand') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.cash_on_hand || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cash on Bank') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.cash_on_bank || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Date') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.date || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Remark') }}:</strong>
                <span class="text-subtitle-1"> {{ setup.remark || '-' }} </span>
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
  title: New Setup
  layout: default
  subject: Auth
  active: 'new-setup'
</route>
