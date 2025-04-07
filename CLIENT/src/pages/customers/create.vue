<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'

const form = reactive({
  data: {
    name: null,
    address: null,
    phone: null,
    email: null,
    date: null,
    job: null,
  },
  options: {
    roles: [],
    customers: [],
  },
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.get('/customers').then(res => {
    form.options = res.data.data
  })
})
const formatDate = date => {
  if (!date) return null
  const d = new Date(date)
  // eslint-disable-next-line newline-before-return
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/customers', form.data)
      .then(res => {
        if (res.status === 200) router.back()
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

const rules = {
  required: v => !!v || 'This field is required',
  phone: v => /^\d{8,15}$/.test(v) || 'Phone number must be valid',
  date: v => !!Date.parse(v) || 'Invalid date format',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Create New Customer')"
    :submitting="submitting"
    @submit="onCreate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onCreate"
    >
      <VRow dense>
        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.data.name"
            :label="$t('Name')"
            :rules="[rules.required]"
            outlined
            required
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.address"
            :label="$t('Address')"
            :rules="[rules.required]"
            :items="[
              'Battambang',
              'Siem Reap',
              'Kampong Thom',
              'Phnom Penh',
              'Kampong Cham',
              'Kampong Chhnang',
              'Kampong Speu',
              'Kampot',
              'Kandal',
              'Koh Kong',
              'Kratie',
              'Mondulkiri',
              'Oddar Meanchey',
              'Pailin',
              'Preah Sihanouk',
              'Preah Vihear',
              'Prey Veng',
              'Pursat',
              'Ratanakiri',
              'Stung Treng',
              'Svay Rieng',
              'Takéo',
              'Tbong Khmum',
            ]"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.phone"
            :label="$t('Phone')"
            :rules="[rules.required, rules.phone]"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.job"
            :label="$t('Job')"
            :rules="[rules.required]"
            :items="[
              'Business',
              'Developer',
              'Designer',
              'Doctor',
              'Engineer',
              'Farmer',
              'Lawyer',
              'Nurse',
              'Police',
              'Teacher',
            ]"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.date"
            :rules="[rules.required, rules.data]"
            :label="$t('Date')"
            type="date"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Customer Create
  layout: default
  subject: Auth
  active: 'customer'
</route>
