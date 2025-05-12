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
const product = ref(null)
const error = ref(false)
const fetchProduct = async () => {
  try {
    const res = await api.get(`/products/${route.query.id}`)
    const data = res.data.data
    product.value = data
  } catch (e) {
    console.error('Failed to fetch product:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchProduct)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Product Details')"
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
          {{ $t('Unable to load product details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: Product Details -->
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
                <span class="text-subtitle-1"> {{ product.model || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Color') }}:</strong>
                <span class="text-subtitle-1"> {{ product.colors || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Filter') }}:</strong>
                <span class="text-subtitle-1"> {{ product.filtration_stage || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cold water Tank Capacity') }}:</strong>
                <span class="text-subtitle-1"> {{ product.cold_water_tank_capacity || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Hot water Tank Capacity') }}:</strong>
                <span class="text-subtitle-1"> {{ product.hot_water_tank_capacity || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Heating Capacity') }}:</strong>
                <span class="text-subtitle-1"> {{ product.heating_capacity || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cooling Capacity') }}:</strong>
                <span class="text-subtitle-1"> {{ product.cooling_capacity || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Cold Power Consumption') }}:</strong>
                <span class="text-subtitle-1"> {{ product.cold_power_consumption || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Hot Power Consumption') }}:</strong>
                <span class="text-subtitle-1"> {{ product.hot_power_consumption || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Quantity') }}:</strong>
                <span class="text-subtitle-1"> {{ product.quantity || '-' }} </span>
              </VCol>
            </VRow>
          </VCol>

          <!-- Right: Image -->
          <VCol
            cols="12"
            md="4"
            class="text-center"
          >
            <img
              :src="constant.storagePath + product.image"
              alt="Product Image"
              style="max-width: 100%; max-height: 300px; object-fit: contain"
            >
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: product View
  layout: default
  subject: Auth
  active: 'product'
</route>
