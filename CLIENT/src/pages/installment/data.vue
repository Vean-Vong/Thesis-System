<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import { useI18n } from 'vue-i18n'
import Swal from 'sweetalert2'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()

const payments = ref([])
const loading = ref(false)
const installmentId = route.query.id

// Sort payments by date
const sortedPayments = computed(() => {
  return payments.value.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
})

// Fetch payment history
const fetchPayments = async () => {
  if (!installmentId) return

  loading.value = true
  try {
    const res = await api.get(`/installments/${installmentId}/payments`)
    payments.value = res.data.data || []
  } catch (error) {
    console.error('❌ Failed to fetch payment history', error)
    Swal.fire('Error', t('Failed to fetch payment history'), 'error')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (installmentId) fetchPayments()
})
</script>

<template>
  <AppFormDetailTemplate :title="$t('Payment History')">
    <VContainer>
      <h2 class="text-2xl font-bold mb-4 Payment_history">{{ t('Payment History') }}</h2>

      <VCard v-if="loading">
        <VCardText>
          <VProgressCircular
            indeterminate
            color="primary"
          />
          <span class="ml-3">{{ $t('Loading data...') }}</span>
        </VCardText>
      </VCard>

      <VTable v-if="!loading && sortedPayments.length > 0">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ t('Price') }}</th>
            <th>{{ t('Date') }}</th>
            <th>{{ t('Month') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(payment, index) in sortedPayments"
            :key="payment.id"
          >
            <td>{{ index + 1 }}</td>
            <td>${{ payment.amount.toLocaleString() }}</td>
            <td>{{ new Date(payment.created_at).toLocaleDateString() }}</td>
            <td>{{ t('Months') }} {{ index + 1 }}</td>
          </tr>
        </tbody>
      </VTable>

      <div v-else-if="!loading">
        <p class="text-gray-500">{{ t('No payment history yet') }}</p>
      </div>
    </VContainer>
  </AppFormDetailTemplate>
</template>

<style>
.Payment_history {
  margin-top: -40px;
  text-align: center;
}
</style>

<route lang="yaml">
meta:
  title: ViewList
  layout: default
  subject: Auth
  active: 'viewList'
</route>
