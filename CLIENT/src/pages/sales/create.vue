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
    warranty: 'Free monthly Maintenance',
    seller: null,
    contract_type: null,
  },
  options: {
    roles: [],
    sales: [],
  },
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.get('/sales').then(res => {
    form.options = res.data.data
  })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  form.data.price = parseFloat(form.data.price.toString().replace(/[^0-9.]/g, '')) || 0
  form.data.discount = parseFloat(form.data.discount.toString().replace(/[^0-9.]/g, '')) || 0

  submitting.value = true
  api
    .post('/sales', form.data)
    .then(res => {
      if (res.status === 200) router.back()
    })
    .catch(err => {
      console.error(err.response?.data || err)
      alert(err.response?.data?.message || 'Failed to create sale')
    })
    .finally(() => {
      submitting.value = false
    })
}

const rules = {
  required: v => !!v || 'This field is required',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Create New Sale')"
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
            :label="$t('Price')"
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
            :items="['0%', '10%', '20%']"
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
            :rules="[rules.required,]"
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
            :items="['6 Months', '1 Year', '3 Years']"
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
            :items="['Vean Vong', 'Dorn Sann', 'Sarun Oueng', 'Chea Selin', 'Phoung Chansophol']"
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
            :items="['purchase', 'installment']"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Sale Create
  layout: default
  subject: Auth
  active: 'sale'
</route>
