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

const fetchUtility_expenses = async () => {
  try {
    const res = await api.get(`/utility_expenses/${route.query.id}`)
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

onMounted(fetchUtility_expenses)

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true
  api
    .put(`/utility_expenses/${route.query.id}`, form.data)
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
    :title="$t('Update Utility_expenses')"
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
            :items="['$130', '$12']"
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
