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

const fetchReports = async () => {
  try {
    const res = await api.get(`/reports/${route.query.id}`)
    console.log('API response:', res.data)

    if (res.data.status === 200) {
      form.data = { ...res.data.data }
    } else {
      console.error('Error fetching utility expense:', res.data.message)
    }
  } catch (error) {
    console.error('Failed to fetch utility expense:', error)
  }
}

onMounted(fetchReports)

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true
  api
    .put(`/reports/${route.query.id}`, form.data)
    .then(res => {
      if (res.data.status === 200) router.back()
    })
    .catch(error => {
      console.error('Error updating sale:', error.response?.data || error)
    })
    .finally(() => {
      submitting.value = false
    })
}

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="6"
    :title="$t('Update Reports')"
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
            :label="$t('Cash on Hand')"
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
            :label="$t('Cash on Bank')"
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
  title: Daily Report Edit
  layout: default
  subject: Auth
  active: 'report '
</route>
