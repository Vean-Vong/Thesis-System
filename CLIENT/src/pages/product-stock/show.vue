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
const stock = ref(null)
const error = ref(false)
const fetchStock = async () => {
  try {
    const res = await api.get(`/stock/${route.query.id}`)
    const data = res.data.data
    stock.value = data
  } catch (e) {
    console.error('Failed to fetch stock:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchStock)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Product Stock Details')"
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
          {{ $t('Unable to load stock details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: stock Details -->
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
                <span class="text-subtitle-1"> {{ stock.model || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Quantity') }}:</strong>
                <span class="text-subtitle-1"> {{ stock.quantity || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Date') }}:</strong>
                <span class="text-subtitle-1"> {{ stock.date || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Supplier') }}:</strong>
                <span class="text-subtitle-1"> {{ stock.supplier || '-' }} </span>
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
  title: stock View
  layout: default
  subject: Auth
  active: 'stock'
</route>
