<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import { useI18n } from 'vue-i18n'
import html2pdf from 'html2pdf.js'

const { t } = useI18n()
const route = useRoute()
const invoice = ref(null)
const loading = ref(false)
const printRef = ref(null)
const invoiceContent = ref(null)

const formatDate = dateStr => {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  const day = d.getDate()
  const month = d.toLocaleString('en-US', { month: 'long' })
  const year = d.getFullYear()

  return `${day} ${month}, ${year}`
}

const fetchInvoice = () => {
  loading.value = true
  api
    .get(`/invoices/${route.query.id}`)
    .then(res => {
      if (res.data.status === 200) {
        invoice.value = res.data.data
      }
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  if (route.query.id) fetchInvoice()
})

const totalCost = computed(() => {
  if (!invoice.value || !invoice.value.utility_expenses) return 0

  return invoice.value.utility_expenses.reduce((sum, expense) => {
    const cost = parseFloat(expense.cost) || 0

    return sum + cost
  }, 0)
})

function printInvoice() {
  window.print()
}

function saveAsPDF() {
  const element = invoiceContent.value?.$el || invoiceContent.value
  if (!element) return
  html2pdf()
    .set({
      margin: 0.5,
      filename: 'invoice.pdf',
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
    })
    .from(element)
    .save()
}
</script>

<template>
  <AppFormDetailTemplate :title="t('Invoice')">
    <VCardText class="text-right">
      <VBtn
        color="primary"
        @click="saveAsPDF"
      >
        <VIcon start>mdi-printer</VIcon>
        {{ t('Print') }}
      </VBtn>
    </VCardText>
    <VCard
      id="invoiceContent"
      ref="invoiceContent"
    >
      <VCardTitle>
        <div class="Title">
          <h4># {{ invoice?.id || '-' }}</h4>
          <h3 class="text-subtitle-1">{{ t('Date') }}: {{ formatDate(invoice?.invoice_date) }}</h3>
        </div>
      </VCardTitle>
      <VDivider />

      <VCardText>
        <div
          v-if="loading"
          class="d-flex justify-center"
        >
          <VProgressCircular
            indeterminate
            color="primary"
            size="48"
          />
        </div>

        <div v-else-if="invoice && invoice.utility_expenses?.length">
          <VList lines="all">
            <VListItem
              v-for="(expense, index) in invoice.utility_expenses"
              :key="expense.id"
              class="mb-2 rounded border"
            >
              <VListItemTitle class="font-weight-bold text-lg"> {{ index + 1 }}. {{ expense.type }} </VListItemTitle>
              <VListItemSubtitle class="text-lg">
                <div>{{ t('Date') }}: {{ formatDate(expense.bill_date) }}</div>
                <div>{{ t('Usage Amount') }}: {{ expense.usage_amount }}</div>
                <div>{{ t('Cost') }}: ${{ parseFloat(expense.cost).toFixed(2) }}</div>
              </VListItemSubtitle>
            </VListItem>
          </VList>

          <div class="text-h4 font-weight-bold text-right">{{ t('Total') }}: ${{ totalCost.toFixed(2) }}</div>
        </div>

        <div
          v-else-if="!loading"
          class="text-center text-medium-emphasis py-8"
        >
          {{ t('No utility expenses found.') }}
        </div>
      </VCardText>
    </VCard>
  </AppFormDetailTemplate>
</template>

<style>
.Title {
  display: flex;
  justify-content: space-between;
}
</style>

<route lang="yaml">
meta:
  title: Utility Expenses Invoice Detail
  layout: default
  subject: Auth
  active: 'utility_expenses '
</route>
