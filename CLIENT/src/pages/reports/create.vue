<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'

const form = reactive({
  data: {
    customer_name: null,
    invoice_number: null,
    unit: null,
    cash_on_hand: null,
    cash_on_bank: null,
    date: null,
    remark: null,
  },
  options: {
    roles: [],
    reports: [],
  },
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.get('/reports').then(res => {
    form.options = res.data.data
  })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true
  api
    .post('/reports', form.data)
    .then(res => {
      if (res.status === 200) router.back()
    })
    .catch(err => {
      console.error(err.response?.data || err)
      alert(err.response?.data?.message || 'Failed to create service')
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
    cols="6"
    :title="$t('Create Reports')"
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
            v-model="form.data.customer_name"
            :label="$t('Customer Name')"
            :rules="[rules.required]"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.data.invoice_number"
            :label="$t('Invoice Number')"
            :rules="[rules.required]"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.unit"
            :rules="[rules.required]"
            :label="$t('Unit')"
            type="number"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.cash_on_hand"
            :label="$t('Cash_on_Hand')"
            :rules="[rules.required]"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.cash_on_bank"
            :label="$t('Cash_on_Bank')"
            :rules="[rules.required]"
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
            :label="$t('date')"
            :rules="[rules.required]"
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
            v-model="form.data.remark"
            :label="$t('Remark')"
            :rules="[rules.required]"
            :items="['Paid', 'Sale']"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Daily Report
  layout: default
  subject: Auth
  active: 'report '
</route>
