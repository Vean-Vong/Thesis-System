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
    <VCard
      v-else
      class="border border-gray-200 shadow-sm rounded-xl"
    >
      <VCardText>
        <VRow dense>
          <div class="employeeInfo">
            <div class="mb-4">
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-account</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Name') }}</p>
                  <h2 class="name">{{ employee.name || '-' }}</h2>
                </div>
              </div>
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-gender-male-female</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Gender') }}</p>
                  <h2 class="name">{{ employee.gender || '-' }}</h2>
                </div>
              </div>
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-briefcase-account</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Position') }}</p>
                  <h2 class="name">{{ employee.position || '-' }}</h2>
                </div>
              </div>
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-email</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Email') }}</p>
                  <h2 class="name">{{ employee.email || '-' }}</h2>
                </div>
              </div>
            </div>
            <div class="mb-4">
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-phone</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Phone') }}</p>
                  <h2 class="name">{{ employee.phone || '-' }}</h2>
                </div>
              </div>
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-map-marker</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Address') }}</p>
                  <h2 class="name">{{ employee.address || '-' }}</h2>
                </div>
              </div>
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-cake-variant</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Date of Birth') }}</p>
                  <h2 class="name">{{ employee.date_of_birth || '-' }}</h2>
                </div>
              </div>
              <div class="container">
                <div class="icon"><VIcon color="indigo">mdi-calendar-check</VIcon></div>
                <div class="title">
                  <p class="names">{{ $t('Hire Date') }}</p>
                  <h2 class="name">{{ employee.hire_date || '-' }}</h2>
                </div>
              </div>
            </div>
          </div>
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
