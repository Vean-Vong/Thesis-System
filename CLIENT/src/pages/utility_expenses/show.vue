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
const utility_expenses = ref(null)
const error = ref(false)
const fetchUtility_expenses = async () => {
  try {
    const res = await api.get(`/utility_expenses/${route.query.id}`)
    const data = res.data.data
    utility_expenses.value = data
  } catch (e) {
    console.error('Failed to fetch utility_expenses:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchUtility_expenses)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Utility_expenses Details')"
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
          {{ $t('Unable to load utility_expenses details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: utility_expenses Details -->
          <VCol
            cols="12"
            md="8"
          >
            <VRow dense>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Type') }}:</strong>
                <span class="text-subtitle-1"> {{ utility_expenses.type || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Bill Date') }}:</strong>
                <span class="text-subtitle-1"> {{ utility_expenses.bill_date || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Usage Amount') }}:</strong>
                <span class="text-subtitle-1"> {{ utility_expenses.usage_amount || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cost') }}:</strong>
                <span class="text-subtitle-1"> {{ utility_expenses.cost || '-' }} </span>
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Unit Rate') }}:</strong>
                <span class="text-subtitle-1"> {{ utility_expenses.unit_rate || '-' }} </span>
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
  title: utility_expenses View
  layout: default
  subject: Auth
  active: 'utility_expenses'
</route>
