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
    model: null,
    invoice_number: null,
    unit: null,
    cash_on_hand: null,
    cash_on_bank: null,
    date: null,
    remark: null,
  },
  options: {
    roles: [],
    setup: [],
  },
})

const refForm = ref()
const submitting = ref(false)

const fetchSetup = async () => {
  try {
    const res = await api.get(`/setup/${route.query.id}`)

    form.data = { ...res.data.data }
  } catch (error) {
    console.error('Failed to fetch sale:', error)
  }
}

onMounted(fetchSetup)

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return
  submitting.value = true
  api
    .put(`/setup/${route.query.id}`, form.data)
    .then(res => {
      if (res.data.status === 200) router.back()
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
    :title="$t('Update Setup')"
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
            required
          />
        </VCol>
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
          class="mt-4"
        >
          <VTextField
            v-model="form.data.invoice_number"
            :label="$t('Invoice Number')"
            :rules="[rules.required]"
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
            :items="['Setup', 'Service', 'Maintenance']"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Edit New Setup
  layout: default
  subject: Auth
  active: 'new-setup'
</route>
