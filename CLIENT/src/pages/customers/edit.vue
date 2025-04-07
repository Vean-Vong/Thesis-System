<!-- eslint-disable newline-before-return -->
<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'
import { useRoute } from 'vue-router'

const route = useRoute()
const refForm = ref()
const submitting = ref(false)

const form = reactive({
  data: {
    name: null,
    address: null,
    phone: null,
    email: null,
    date: null,
    job: null,
  },
})

const fetchCustomer = async () => {
  try {
    const res = await api.get(`/customers/${route.query.id}`)
    form.data = res.data.data
  } catch (error) {
    console.error('Failed to fetch customer:', error)
  }
}

onMounted(fetchCustomer)

const formatDate = date => {
  if (!date) return null
  const d = new Date(date)
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}

const onUpdate = async () => {
  form.data.date = formatDate(form.data.date) // Ensure correct date format

  const validation = await refForm.value?.validate()
  if (!validation.valid) return

  submitting.value = true
  try {
    await api.put(`/customers/${route.query.id}`, form.data)
    router.back()
  } catch (error) {
    console.error('Update failed:', error)
  } finally {
    submitting.value = false
  }
}

const rules = {
  required: v => !!v || 'This field is required',
  phone: v => /^\d{8,15}$/.test(v) || 'Phone number must be valid',
  date: v => !!Date.parse(v) || 'Invalid date',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="6"
    :title="$t('Update Customer')"
    :submitting="submitting"
    @submit="onUpdate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onUpdate"
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
            :rules="[rules.required, rules.date]"
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
  title: Customer Edit
  layout: default
  subject: Auth
  active: 'customer'
</route>
