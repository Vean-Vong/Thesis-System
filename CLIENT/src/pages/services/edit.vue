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
    model: null,
    price: null,
    date: null,
    duration: null,
    warranty: 'Free monthly Maintenance',
    seller: null,
    contract_type: null,
  },
  options: {
    roles: [],
    services: [],
  },
})

const refForm = ref()
const submitting = ref(false)

const fetchServices = async () => {
  try {
    const res = await api.get(`/services/${route.query.id}`)
    if (res.data.status === 200) {
      form.data = { ...res.data.data }
    } else {
      console.error('Error fetching sale:', res.data.message)
    }
  } catch (error) {
    console.error('Failed to fetch sale:', error)
  }
}

onMounted(fetchServices)

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  form.data.price = parseFloat(form.data.price.toString().replace(/[^0-9.]/g, '')) || 0

  submitting.value = true
  api
    .put(`/services/${route.query.id}`, form.data)
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
    :title="$t('Update Service')"
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
            :items="['$12', '$130']"
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
            :items="['Unlimited', 'Once a year']"
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
            :items="['Monthly Fees', 'Yearly Fees']"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Edit Service
  layout: default
  subject: Auth
  active: 'service'
</route>
