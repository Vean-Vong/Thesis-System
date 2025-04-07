<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'

const form = reactive({
  data: {
    model: null,
    price: null,
    discount: null,
    date: null,
    duration: null,
    seller: null,
    warranty: 'Free monthly Maintenance',
    contract_type: 'rental',
  },
  options: {
    roles: [],
    rentals: [],
  },
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.get('/rentals').then(res => {
    form.options = res.data.data
  })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/rentals', form.data)
      .then(res => {
        console.log(res) // Log response to debug
        if (res.status === 200) router.back()
      })
      .catch(error => {
        console.error('Error:', error.response?.data || error.message)
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

const rules = {
  required: v => !!v || 'This field is required',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Create New Rental')"
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
          <VSelect
            v-model="form.data.model"
            :label="$t('Model')"
            :rules="[rules.required]"
            :items="[
              'GP-80B',
              'GP-900',
              'GP-50',
              'G-6000C',
              'GP-900S',
              'GP-500S',
              'GP-80S',
              'GP-700S',
              'Maxtream',
              'Under-Sink-Case',
            ]"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.price"
            :label="$t('Rental Price')"
            :rules="[rules.required]"
            :items="['$720', '$490', '$540', '$190', '$790']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.discount"
            :label="$t('Discount')"
            :rules="[rules.required]"
            :items="['10%', '20%']"
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
            :rules="[rules.required]"
            :label="$t('Date')"
            type="date"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.duration"
            :label="$t('Duration')"
            :rules="[rules.required]"
            :items="['Unlimited']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.warranty"
            :label="$t('Warranty')"
            :rules="[rules.required]"
            :items="['Free monthly Maintenance']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.seller"
            :label="$t('Seller')"
            :rules="[rules.required]"
            :items="[
              'Mr. John Doe',
              'Mr. Jane Doe',
              'Mr. John Smith',
              'Mr. Jane Smith',
              'Mr. John Doe',
              'Mr. Jane Doe',
            ]"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.contract_type"
            :label="$t('Contract Type')"
            :rules="[rules.required]"
            :items="['rental']"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Rental Create
  layout: default
  subject: Auth
  active: 'rental'
</route>
