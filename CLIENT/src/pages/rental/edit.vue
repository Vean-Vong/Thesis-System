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

const submitting = ref(false)
const refForm = ref()

const fetchRentals = async () => {
  try {
    const res = await api.get(`/rentals/${route.query.id}`)
    form.data = res.data.data
  } catch (error) {
    console.error('Failed to fetch rentals:', error)
  }
}

onMounted(fetchRentals)

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .put(`/rentals/${route.query.id}`, form.data)
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
  required: v => !!v || 'This field is required',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Update rentals')"
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
            :label="$t('Rental Price')"
            :rules="[rules.required]"
            :items="['$28', '$27', '$25', '$23', '$17']"
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
            :items="['Free first month', 'None']"
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
  title: Rental Edit
  layout: default
  subject: Auth
  active: 'rental'
</route>
