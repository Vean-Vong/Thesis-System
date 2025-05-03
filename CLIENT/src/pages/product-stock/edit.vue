<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router' // ✅ Import route and router
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'

const route = useRoute()
const router = useRouter()

const form = reactive({
  data: {
    model: null,
    quantity: null,
    date: null,
    supplier: null,
  },
})

const refForm = ref()
const submitting = ref(false)

const fetchStock = async () => {
  try {
    const res = await api.get(`/stock/${route.query.id}`)
    if (res.data.status === 200) {
      Object.assign(form.data, res.data.data)
    } else {
      console.error('Error fetching stock:', res.data.message)
    }
  } catch (error) {
    console.error('Failed to fetch stock:', error)
  }
}
onMounted(fetchStock)

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true
  try {
    const res = await api.put(`/stock/${route.query.id}`, form.data)
    if (res.data.status === 200) {
      router.back()
    }
  } catch (error) {
    console.error('Error updating stock:', error.response?.data || error)
  } finally {
    submitting.value = false
  }
}

const rules = {
  required: v => !!v || 'This field is required',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Update Stock-in')"
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
            v-model="form.data.quantity"
            :label="$t('Quantity')"
            :rules="[rules.required]"
            :items="Array.from({ length: 10 }, (_, i) => (i + 1).toString())"
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
            v-model="form.data.supplier"
            :label="$t('Supplier')"
            :rules="[rules.required]"
            :items="['Head Office']"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Product Stock Edit
  layout: default
  subject: Auth
  active: 'product-stock'
</route>
