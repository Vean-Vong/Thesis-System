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
const filters = ref(null)
const error = ref(false)
const fetchFilters = async () => {
  try {
    const res = await api.get(`/filters/${route.query.id}`)
    const data = res.data.data
    filters.value = data
  } catch (e) {
    console.error('Failed to fetch filters:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchFilters)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Filters Stock Details')"
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
          {{ $t('Unable to load filters details.') }}
        </VAlert>
      </VCardText>
    </VCard>

    <!-- Loaded Data -->
    <VCard v-else>
      <VCardText>
        <VRow>
          <!-- Left: filters Details -->
          <VCol
            cols="12"
            md="8"
          >
            <VRow dense>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Sediment') }}:</strong>
                <span class="text-subtitle-1"> {{ filters.sediment || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Pre-Carbon') }}:</strong>
                <span class="text-subtitle-1"> {{ filters.pre_carbon || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('UF_Membrane') }}:</strong>
                <span class="text-subtitle-1"> {{ filters.uf_membrane || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Post-Carbon') }}:</strong>
                <span class="text-subtitle-1"> {{ filters.post_carbon || '-' }} </span>
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <strong>{{ $t('Date') }}:</strong>
                <span class="text-subtitle-1"> {{ filters.date || '-' }} </span>
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
  title: filters View
  layout: default
  subject: Auth
  active: 'filters'
</route>
