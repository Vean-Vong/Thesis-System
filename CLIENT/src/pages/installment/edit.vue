<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'
import { useRoute } from 'vue-router'

const route = useRoute()

// Form reactive state
const form = reactive({
  customer_name: '',
  invoice_number: '',
  model: '',
  price: '',
  deposit: '',
  duration: '',
  monthly_fee: '',
  total_price: '',
  paid_amount: '',
  balance: '',
  seller: '',
  contract_type: '',
  warranty: '',
  status: '',
})

const refForm = ref()
const submitting = ref(false)

// Fetch the installment data
const fetchInstallment = async () => {
  const id = route.params.id || route.query.id
  if (!id) {
    console.error('❌ No ID found in route!')
    error.value = true

    return
  }

  try {
    const res = await api.get(`/installments/${id}`)
    const data = res.data.data

    // Populate form
    form.customer_name = data.customer.name
    form.invoice_number = data.sale.invoice_number
    form.model = data.sale.model
    form.price = data.sale.price
    form.deposit = data.deposit
    form.duration = data.sale.duration

    form.monthly_fee = data.sale?.products?.[0]?.install_price || 0

    form.total_price = data.total_price
    form.paid_amount = data.paid_amount
    form.seller = data.sale.seller
    form.contract_type = data.sale.contract_type
    form.warranty = data.sale.warranty
    form.status = data.status
  } catch (e) {
    console.error('Failed to fetch installment:', e)
    error.value = true
  } finally {
    loading.value = false
  }
}

const onUpdate = async () => {
  submitting.value = true
  const id = route.params.id || route.query.id

  try {
    await api.put(`/installments/${id}`, form)
    router.back()
  } catch (err) {
    console.error('Error updating installment:', err.response?.data || err)
  } finally {
    submitting.value = false
  }
}
const installPrice = computed(() => Number(form.price) || 0)
const duration = computed(() => Number(form.duration) || 36)
const paidAmount = computed(() => Number(form.paid_amount) || 0)

const calculatedBalance = computed(() => {
  const total = installPrice.value * duration.value
  const remaining = total - paidAmount.value

  return remaining > 0 ? remaining : 0
})
onMounted(fetchInstallment)

// Validation rules
const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="6"
    :title="$t('Update Installment')"
    :submitting="submitting"
    @submit="onUpdate"
  >
    <VCard v-if="loading">
      <VCardText>
        <VProgressCircular
          indeterminate
          color="primary"
        />
        <span class="ml-3">{{ $t('Loading data...') }}</span>
      </VCardText>
    </VCard>

    <VCard v-else-if="error">
      <VCardText>
        <VAlert
          type="error"
          color="error"
          >Unable to load installment details.</VAlert
        >
      </VCardText>
    </VCard>

    <VRow dense>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.customer_name"
          :label="$t('Customer Name')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.invoice_number"
          :label="$t('Invoice No.')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.model"
          :label="$t('Model')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.price"
          :label="$t('Price')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.deposit"
          :label="$t('Deposit')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.monthly_fee"
          :label="$t('Monthly Fee')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.paid_amount"
          :label="$t('Already Paid')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.duration"
          :label="$t('Duration')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.contract_type"
          :label="$t('Contract Type')"
          outlined
        />
      </VCol>
      <VCol
        cols="12"
        class="mt-4"
        md="6"
      >
        <VTextField
          v-model="form.seller"
          :label="$t('Seller')"
          outlined
        />
      </VCol>
    </VRow>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Edit Installment
  layout: default
  subject: Auth
  active: 'installment'
</route>
