<script setup>
import { ref, computed, onMounted } from 'vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'
// eslint-disable-next-line import/no-unresolved
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const items = ref([])
const selectedExpenses = ref([])
const search = ref(null)
const loading = ref(false)
const delete_item = ref(null)
const deleting = ref(false)
const dialog = ref(false)
const printRef = ref(null)

const invoiceDate = ref(new Date().toISOString().slice(0, 10))
const creatingInvoice = ref(false)

const meta = ref({
  current_page: 1,
  from: 1,
  last_page: 1,
  per_page: 15,
  to: 1,
  total: 0,
})

const formatDate = bill_date => {
  if (!bill_date) return ''
  const d = new Date(bill_date)
  const day = d.getDate()
  const month = d.toLocaleString('en-US', { month: 'long' })
  const year = d.getFullYear()

  return `${day} ${month}, ${year}`
}

const initData = () => {
  loading.value = true
  api
    .get('/utility_expenses', {
      params: {
        page: meta.value.current_page,
        limit: meta.value.per_page,
        search: search.value,
      },
    })
    .then(res => {
      const paginated = res.data.data
      meta.value = {
        current_page: paginated.current_page,
        from: paginated.from,
        last_page: paginated.last_page,
        per_page: paginated.per_page,
        to: paginated.to,
        total: paginated.total,
      }
      items.value = paginated.data.map(utility_expense => {
        let unit = ''

        return {
          ...utility_expense,
          cost: `$${parseFloat(utility_expense.cost).toLocaleString(undefined, {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
          })}`,
          bill_date: formatDate(utility_expense.bill_date),
          unit_rate: `${parseFloat(utility_expense.unit_rate).toLocaleString(undefined, {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
          })} ${unit}`,
        }
      })
      selectedExpenses.value = []
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  initData()
})

const cancelCallback = () => {
  dialog.value = false
  delete_item.value = null
}

const confirmDeleteCallback = () => {
  deleting.value = true
  api
    .delete(`/utility_expenses/${delete_item.value}`)
    .then(res => {
      if (res.status === 200) {
        initData()
      }
    })
    .catch(error => {
      console.error('Delete request failed:', error.response?.data || error)
    })
    .finally(() => {
      deleting.value = false
      delete_item.value = null
      dialog.value = false
    })
}

const totalCost = computed(() => {
  return items.value.reduce((sum, expense) => {
    const numericCost = parseFloat(expense.cost.replace(/[^0-9.-]+/g, '')) || 0

    return sum + numericCost
  }, 0)
})

function createInvoiceDirectly() {
  creatingInvoice.value = true
  api
    .post('/invoices', {
      invoice_date: invoiceDate.value,
      utility_expense_ids: selectedExpenses.value,
    })
    .then(res => {
      if (res.data.status === 200) {
        console.log('Invoice ID:', res.data.data.id)
        selectedExpenses.value = []
        router.push({ name: 'utility_expenses-invoiceDetail', query: { id: res.data.data.id } })
      }
    })
    .finally(() => {
      creatingInvoice.value = false
    })
}
</script>

<template>
  <AppFormDetailTemplate :title="$t('Create Invoice')">
    <ConfirmDialog
      v-model="dialog"
      :deleting="deleting"
      @on-cancel="cancelCallback"
      @on-confirm-delete="confirmDeleteCallback"
    />

    <VRow
      justify="space-between"
      align="center"
    >
      <VCol cols="auto">
        <VTextField
          v-model="invoiceDate"
          label="Date"
          type="date"
          style="max-width: 200px"
        />
      </VCol>

      <VCol cols="auto">
        <VBtn
          color="primary"
          :disabled="selectedExpenses.length === 0 || creatingInvoice"
          :loading="creatingInvoice"
          @click="createInvoiceDirectly"
        >
          {{ t('Create Invoice') }}
        </VBtn>
      </VCol>
    </VRow>

    <div ref="printRef">
      <VDataTable
        :items="items"
        :loading="loading"
        :headers="[
          {
            text: '',
            value: 'select',
            width: '50px',
            sortable: false,
            align: 'center',
          },
          { text: t('#'), value: 'index' },
          { text: t('Type'), value: 'type' },
          { text: t('Bill Date'), value: 'bill_date' },
          { text: t('Usage Amount'), value: 'usage_amount' },
          { text: t('Cost'), value: 'cost' },
        ]"
        :items-per-page="meta.per_page"
        class="elevation-1"
        item-key="id"
        :page="meta.current_page"
        hide-default-footer
      >
        <template #loading>
          <div class="d-flex flex-column align-center justify-center py-10">
            <VProgressCircular
              indeterminate
              color="primary"
              size="48"
            />
            <div class="mt-4 text-subtitle-1 text-medium-emphasis">
              {{ t('Loading data...') }}
            </div>
          </div>
        </template>

        <template #item.select="{ item }">
          <VCheckbox
            v-model="selectedExpenses"
            :value="item.id"
            color="primary"
            hide-details
            dense
          />
        </template>

        <template #item.index="{ index }">
          {{ meta.from + index }}
        </template>
      </VDataTable>

      <div
        class="total-cost"
        style="margin: 1rem 0; font-weight: bold; font-size: 1.2rem"
      >
        {{ t('Total') }}: ${{ totalCost.toFixed(2) }}
      </div>
    </div>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: Utility Expenses
  layout: default
  subject: Auth
  active: 'utility_expenses '
</route>
