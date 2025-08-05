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
const rental = ref(null)
const error = ref(false)
const fetchRental = async () => {
  try {
    const res = await api.get(`/rentals/${route.query.id}`)
    const data = res.data.data
    rental.value = data
  } catch (e) {
    console.error('Failed to fetch rental:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchRental)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Rental Details')"
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
          {{ $t('Unable to load rental details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: rental Details -->
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
                <span class="text-subtitle-1"> {{ rental.model || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Price') }}:</strong>
                <span class="text-subtitle-1"> {{ rental.price || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Discount') }}:</strong>
                <span class="text-subtitle-1"> {{ rental.discount || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Date') }}:</strong>
                <span class="text-subtitle-1"> {{ rental.date || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Duration') }}:</strong>
                <span class="text-subtitle-1"> {{ rental.duration || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Warranty') }}:</strong>
                <span class="text-subtitle-1"> {{ rental.warranty || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Seller') }}:</strong>
                <span class="text-subtitle-1"> {{ rental.seller || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Contract Type') }}:</strong>
                <span class="text-subtitle-1"> {{ rental.contract_type || '-' }} </span>
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
  title: rental View
  layout: default
  subject: Auth
  active: 'rental'
</route>
