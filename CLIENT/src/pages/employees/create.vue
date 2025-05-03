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
    gender: null,
    position: null,
    email: null,
    phone: null,
    address: null,
    date_of_birth: null,
    hire_date: null,
  },
  options: {
    roles: [],
    employees: [],
  },
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.get('/employees').then(res => {
    form.options = res.data.data
  })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/employees', form.data)
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
  email: v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
  dob: v => !!v || 'Date of Birth is required',
  hire_date: v => !!v || 'Hire Date is required',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Create New Employee')"
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
            :rules="[rules.required]"
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
            v-model="form.data.date_of_birth"
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
  title: Employee Create
  layout: default
  subject: Auth
  active: 'employee'
</route>
