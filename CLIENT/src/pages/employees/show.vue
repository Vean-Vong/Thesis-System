<!-- eslint-disable import/no-unresolved -->
<!-- eslint-disable import/extensions -->
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/utilites'

const route = useRoute()
const loading = ref(true)
const employee = ref(null)
const error = ref(false)
const fetchEmployee = async () => {
  try {
    const res = await api.get(`/employees/${route.query.id}`)
    const data = res.data.data
    employee.value = data
  } catch (e) {
    console.error('Failed to fetch employee:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(fetchEmployee)
</script>

<template>
  <AppFormDetailTemplate
    cols="6"
    :title="$t('Employee Details')"
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
          {{ $t('Unable to load employee details.') }}
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
            <strong>{{ $t('Name') }}:</strong> {{ employee.name || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Gender') }}:</strong> {{ employee.gender || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Position') }}:</strong> {{ employee.position || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Email') }}:</strong> {{ employee.email || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Phone') }}:</strong> {{ employee.phone || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Address') }}:</strong> {{ employee.address || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Date of Birth') }}:</strong> {{ employee.date_of_birth || '-' }}
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <strong>{{ $t('Hire Date') }}:</strong> {{ employee.date_of_birth || '-' }}
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: Employee View
  layout: default
  subject: Auth
  active: 'employee'
</route>
