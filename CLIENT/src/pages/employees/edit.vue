<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'
import { useRoute } from 'vue-router'

const route = useRoute()

const form = reactive({
  data: {
    name: null,
    gender: null,
    position: null,
    email: null,
    phone: null,
    address: null,
    date_of_birth: null,
    hire_date: null,
  },
})

const submitting = ref(false)
const refForm = ref()

const fetchEmployee = async () => {
  try {
    const res = await api.get(`/employees/${route.query.id}`)
    form.data = res.data.data
  } catch (error) {
    console.error('Failed to fetch employee:', error)
  }
}

onMounted(fetchEmployee)

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .put(`/employees/${route.query.id}`, form.data)
      .then(res => {
        if (res.status === 200) router.back()
      })
      .catch(error => {
        console.error('Error updating employee:', error)
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
  email: v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
  dob: v => !!v || 'Date of Birth is required',
  hire_date: v => !!v || 'Hire Date is required',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Update Employee')"
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
            v-model="form.data.gender"
            :label="$t('Gender')"
            :rules="[rules.required]"
            :items="['Male', 'Female', 'Other']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.position"
            :label="$t('Position')"
            :rules="[rules.required]"
            :items="['Manager', 'Supervisor', 'Staff', 'Accounting']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.email"
            :label="$t('Email')"
            :rules="[rules.required, rules.email]"
            type="email"
            outlined
            required
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
            :rules="[rules.required]"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
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
            v-model="form.data.date_of_birth"
            :rules="[rules.required, rules.dob]"
            :label="$t('Date of Birth')"
            type="date"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.hire_date"
            :rules="[rules.required, rules.hire_date]"
            :label="$t('Hire Date')"
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
  title: Employee Edit
  layout: default
  subject: Auth
  active: 'employee'
</route>
