<!-- eslint-disable vue/valid-v-slot -->
<template>
  <VContainer id="printableReport">
    <!-- Header & Company Info -->
    <div class="text-center mb-4">
      <img
        :src="Logo"
        alt="Company Logo"
        style="max-width: 100px; margin: auto"
      />
      <h1 class="text-3xl font-bold">{{ $t('ហ្គាន់សាន់ ខូអិលធីឌី') }}</h1>
      <p class="text-gray-500">{{ $t('GANG SAN CO., LTD') }}</p>
      <p class="text-gray-500 www">admin@gangsan.com.kh | www.gangsan.com.kh</p>
      <VAlert
        color="primary"
        variant="tonal"
        class="mt-4 text-white"
      >
        <h2>{{ $t('Total Sales Report') }}</h2>
      </VAlert>
    </div>

    <!-- Buttons -->
    <div class="print-button">
      <VBtn
        color="primary"
        class="mt-5 mr-3"
        @click="printReport"
        >{{ $t('Print') }}</VBtn
      >

      <VBtn
        color="success"
        class="mt-5"
        @click="exportCSV"
        >{{ $t('Save') }}ជា CSV</VBtn
      >
    </div>

    <!-- Filters -->
    <VRow class="mt-5 print-hide">
      <VCol
        cols="12"
        md="3"
      >
        <VTextField
          v-model="filters.start_date"
          :label="$t('Start Date')"
          type="date"
          outlined
          dense
        />
      </VCol>
      <VCol
        cols="12"
        md="3"
      >
        <VTextField
          v-model="filters.end_date"
          :label="$t('End Date')"
          type="date"
          outlined
          dense
        />
      </VCol>
    </VRow>

    <!-- Filters (Visible only on print) -->
    <div
      class="print-filters mt-4 mb-4"
      style="display: none"
    >
      <p>
        <strong>{{ $t('Start Date') }}:</strong> {{ formatDate(filters.start_date) || '-' }}
        <strong>{{ $t('End Date') }}:</strong> {{ formatDate(filters.end_date) || '-' }}
      </p>
    </div>

    <!-- Generate Report Button -->
    <div class="print-buttons">
      <VBtn
        color="primary"
        :loading="loading"
        variant="text"
        class="mt-5 mr-3 border border-blue-300 text-blue-800"
        @click="fetchReport"
      >
        {{ $t('Generate Report') }}
      </VBtn>
    </div>
    <VDataTable
      :headers="headers"
      :items="reportData"
      :items-per-page="-1"
      hide-default-footer
      class="mt-6"
      :loading="loading"
      :loading-text="$t('Loading data...')"
      :no-data-text="$t('No data available')"
    >
      <!-- Null-safe slots -->
      <template #item.quantity="{ item }">
        {{ item.quantity ?? '-' }}
      </template>

      <template #item.contract_type="{ item }">
        {{ item.contract_type ?? '-' }}
      </template>

      <template #item.model="{ item }">
        {{ item.model ?? '-' }}
      </template>

      <template #item.customer_name="{ item }">
        {{ item.customer_name ?? '-' }}
      </template>

      <template #item.price="{ item }">
        {{ item.price ? formatCurrency(item.price) : '-' }}
      </template>

      <!-- Already included -->
      <template #item.grand_total="{ item }">
        <span class="font-bold">{{ formatCurrency(item.grand_total) }}</span>
      </template>

      <!-- Bottom summary -->
      <template #bottom>
        <VDivider />
        <tbody class="d-flex justify-end">
          <tr>
            <td>
              <strong>{{ $t('Quantity') }}:</strong> {{ totalModels }}
            </td>

            <td class="text-end ps-5">
              <strong>{{ $t('Grand_Total') }}:</strong> {{ formatCurrency(grandSubtotal) }}
            </td>
          </tr>
        </tbody>
      </template>
    </VDataTable>
  </VContainer>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-unresolved
import Logo from '@/assets/images/logo.png'
import { VDivider } from 'vuetify/lib/components/index.mjs'

const { t } = useI18n()

const today = new Date()
const oneWeekAgo = new Date()
oneWeekAgo.setDate(today.getDate() - 7)

const filters = ref({
  start_date: oneWeekAgo.toISOString().split('T')[0], // e.g. 2025-07-26
  end_date: today.toISOString().split('T')[0], // e.g. 2025-08-02
  group_by: 'day',
  sort: 'asc',
})

const groupByOptions = ['day', 'month', 'year']

const headers = [
  { title: t('Date'), key: 'date', align: 'start' },
  { title: t('Invoice Number'), key: 'invoice_number', align: 'start' },
  { title: t('Customer Name'), key: 'customer_name', align: 'start' },
  { title: t('Model'), key: 'model', align: 'start' },
  { title: t('Price'), key: 'price', align: 'center' },
  { title: t('Contract Type'), key: 'type', align: 'center' },
  { title: t('Quantity'), key: 'quantity', align: 'center' },
]

const reportData = ref([])
const loading = ref(false)

// Fetch data
async function fetchReport() {
  loading.value = true
  try {
    const res = await api.get('/reports/new-setup-details', {
      params: {
        start_date: filters.value.start_date,
        end_date: filters.value.end_date,
      },
    })

    if (res.status === 200 && res.data.status === 200) {
      reportData.value = res.data.data
      console.log(res)
    } else {
      alert(t('Failed to fetch report data'))
    }
  } catch (error) {
    console.error(error)
    alert(t('Failed to fetch report data'))
  } finally {
    loading.value = false
  }
}

const grandSubtotal = computed(() => reportData.value.reduce((sum, item) => sum + (Number(item.price) || 0), 0))

const totalModels = computed(() => reportData.value.length)

// Format functions
function formatCurrency(amount) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(amount)
}

function formatDate(dateStr) {
  if (!dateStr) return ''

  return new Date(dateStr).toLocaleDateString()
}

// Print Report
function printReport() {
  const printContents = document.getElementById('printableReport').innerHTML
  const originalContents = document.body.innerHTML
  document.body.innerHTML = printContents
  window.print()
  document.body.innerHTML = originalContents
  window.location.reload()
}

// Export as CSV
function exportCSV() {
  if (!reportData.value.length) {
    alert(t('No data available to export'))

    return
  }

  const csvHeaders = headers.map(h => `"${h.title}"`).join(',')
  const csvRows = reportData.value.map(row =>
    [
      `"${row.invoice_number ?? '-'}"`,
      `"${row.customer_name ?? '-'}"`,
      `"${row.model ?? '-'}"`,
      `"${row.quantity ?? '-'}"`,
      `"${row.price ?? '-'}"`,
      `"${row.date ?? '-'}"`,
    ].join(','),
  )

  const csvContent = [csvHeaders, ...csvRows].join('\n')
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  link.href = URL.createObjectURL(blob)
  link.setAttribute('download', 'new_setup_report.csv')
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}
</script>

<style>
@media print {
  .print-buttons,
  .print-button {
    display: none !important;
  }
  .print-filters {
    display: block !important;
  }
  .print-hide {
    display: none !important;
  }
}

.print-button {
  display: flex;
  justify-content: flex-end;
  margin-top: -20px;
}
.www {
  margin-top: -20px;
}
</style>

<route lang="yaml">
meta:
  title: New Setup
  layout: default
  subject: Auth
  active: 'new_setup'
</route>
