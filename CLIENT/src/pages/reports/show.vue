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
const report = ref(null)
const error = ref(false)
const fetchReport = async () => {
  try {
    const res = await api.get(`/reports/${route.query.id}`)
    const data = res.data.data
    report.value = data
  } catch (e) {
    console.error('Failed to fetch report:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchReport)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Report Details')"
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
          {{ $t('Unable to load report details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: report Details -->
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
                <span class="text-subtitle-1"> {{ report.customer_name || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Invoice Number') }}:</strong>
                <span class="text-subtitle-1"> {{ report.invoice_number || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Unit') }}:</strong>
                <span class="text-subtitle-1"> {{ report.unit || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cash on Hand') }}:</strong>
                <span class="text-subtitle-1"> {{ report.cash_on_hand || '-' }} </span>
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cash on Bank') }}:</strong>
                <span class="text-subtitle-1"> {{ report.cash_on_bank || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Date') }}:</strong>
                <span class="text-subtitle-1"> {{ report.date || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Remark') }}:</strong>
                <span class="text-subtitle-1"> {{ report.remark || '-' }} </span>
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
  title: report View
  layout: default
  subject: Auth
  active: 'report'
</route>
