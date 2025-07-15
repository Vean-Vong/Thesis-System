<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'

const form = reactive({
  data: {
    type: null,
    bill_date: null,
    usage_amount: null,
    cost: null,
    unit_rate: null,
  },
  options: {
    roles: [],
    utility_expenses: [],
  },
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.get('/utility_expenses').then(res => {
    form.options = res.data.data
  })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true
  api
    .post('/utility_expenses', form.data)
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
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="6"
    :title="$t('Create Utility_expenses')"
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
            v-model="form.data.type"
            :label="$t('Type')"
            :rules="[rules.required]"
            :items="['Water', 'Electricity', 'Garbage']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.data.bill_date"
            :label="$t('Bill Date')"
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
          <VTextField
            v-model="form.data.usage_amount"
            :rules="[rules.required]"
            :label="$t('Usage Amount')"
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
            v-model="form.data.cost"
            :label="$t('Cost')"
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
            v-model="form.data.unit_rate"
            :label="$t('Unit Rate')"
            :rules="[rules.required]"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Utility Expenses
  layout: default
  subject: Auth
  active: 'utility_expenses '
</route>
