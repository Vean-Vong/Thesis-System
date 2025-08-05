<!-- eslint-disable vue/valid-v-slot -->
<template>
  <VContainer id="printableReport">
    <div class="text-center mb-4">
      <img
        :src="Logo"
        alt="Company Logo"
        style="max-width: 100px; margin: auto"
      />
      <h1 class="text-3xl font-bold">
        {{ $t('ហ្គាន់សាន់ ខូអិលធីឌី') }}
      </h1>
      <p class="text-gray-500">
        {{ $t('GANG SAN CO., LTD') }}
      </p>
      <p class="text-gray-500 www">admin@gangsan.com.kh | www.gangsan.com.kh</p>
      <VAlert
        color="primary"
        variant="tonal"
        class="mt-4 text-white"
      >
        <h2>{{ $t('Total Income Report') }}</h2>
      </VAlert>
    </div>
    <!-- Buttons -->
    <div class="print-button">
      <VBtn
        color="primary"
        class="mt-5 mr-3"
        @click="printReport"
      >
        {{ $t('Print') }}
      </VBtn>

      <VBtn
        color="success"
        class="mt-5"
        @click="exportExcel"
      >
        {{ $t('Save') }}ជា CSV
      </VBtn>
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

    <!-- Filters shown for printing -->
    <div
      class="print-filters mt-4 mb-4"
      style="display: none"
    >
      <p>
        <strong>{{ $t('Start Date') }}:</strong> {{ formatDate(filters.start_date) || '-' }}
        <strong>{{ $t('End Date') }}:</strong> {{ formatDate(filters.end_date) || '-' }}
      </p>
    </div>

    <!-- Buttons -->
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

    <!-- Table -->
    <VDataTable
      :headers="headers"
      :items="reportData"
      :items-per-page="-1"
      hide-default-footer
      class="mt-6"
      :loading="loading"
      :loading-text="$t('Loading data...')"
      :no-data-text="$t('No data available')"
      show-bottom
    >
      <template #item.grand_total="{ item }">
        <span class="font-bold">{{ formatCurrency(item.grand_total) }}</span>
      </template>
      <template #bottom>
        <VDivider />
        <tr class="line mt-3">
          <td>
            <h3>{{ $t('Grand_Total') }} :</h3>
          </td>
          <td>
            <h3>{{ formatCurrency(grandSubtotal) }}</h3>
          </td>
        </tr>
      </template>
    </VDataTable>

    <!-- Chart -->
    <VCard
      v-if="chartData.labels.length"
      class="mt-6 print-hide"
    >
      <VCardTitle>{{ $t('Sales | Rentals | Installments | Chart') }}</VCardTitle>
      <VCardText>
        <canvas id="reportChart" />
      </VCardText>
    </VCard>
  </VContainer>
</template>

<script setup>
import Swal from 'sweetalert2'
import * as XLSX from 'xlsx'
const { t } = useI18n()
// eslint-disable-next-line import/no-duplicates
import { ref, watch, nextTick } from 'vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-named-as-default
import Chart from 'chart.js/auto'
// eslint-disable-next-line import/no-unresolved
import Logo from '@/assets/images/logo.png'

const today = new Date()
const oneWeekAgo = new Date()
oneWeekAgo.setDate(today.getDate() - 7)

const filters = ref({
  start_date: oneWeekAgo.toISOString().split('T')[0], // e.g. 2025-07-26
  end_date: today.toISOString().split('T')[0], // e.g. 2025-08-02
  group_by: 'day',
  sort: 'asc',
})

const headers = [
  { title: t('Date'), key: 'period', align: 'start' },
  { title: t('Total Sales'), key: 'total_sales', align: 'center' },
  { title: t('Total Installments'), key: 'total_installment', align: 'center' },
  { title: t('Total Rentals'), key: 'total_rentals', align: 'center' },
  { title: t('Grand Total'), key: 'grand_total', align: 'end' },
]

const reportData = ref([])
const loading = ref(false)

const groupByOptions = ['day', 'month', 'year']

const chartData = ref({
  labels: [],
  sales: [],
  installments: [],
  rentals: [],
})

let chartInstance = null

async function fetchReport() {
  loading.value = true
  try {
    const res = await api.get('/sale/report', { params: filters.value })

    if (res.status === 200 && res.data.status === 200) {
      const raw = res.data.data

      reportData.value = Object.keys(raw).map(period => ({
        period,
        ...raw[period],
        total_sales: Number(raw[period].total_sales) || 0,
        total_installment: Number(raw[period].total_installment) || 0,
        total_rentals: Number(raw[period].total_rentals) || 0,
        grand_total: Number(raw[period].grand_total) || 0,
      }))

      prepareChartData()
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

const grandSubtotal = computed(() => {
  return reportData.value.reduce((sum, item) => {
    return sum + item.total_sales + item.total_installment + item.total_rentals
  }, 0)
})

function prepareChartData() {
  chartData.value.labels = reportData.value.map(item => item.period)
  chartData.value.sales = reportData.value.map(item => item.total_sales)
  chartData.value.installments = reportData.value.map(item => item.total_installment)
  chartData.value.rentals = reportData.value.map(item => item.total_rentals)

  renderChart()
}

async function renderChart() {
  await nextTick()

  const ctx = document.getElementById('reportChart')
  if (!ctx) {
    console.error('Canvas element not found')

    return
  }

  if (chartInstance) {
    chartInstance.destroy()
  }

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: chartData.value.labels,
      datasets: [
        {
          label: t('Total Sales'),
          data: chartData.value.sales,
          backgroundColor: '#42a5f5',
        },
        {
          label: t('Total Installments'),
          data: chartData.value.installments,
          backgroundColor: '#66bb6a',
        },
        {
          label: t('Total Rentals'),
          data: chartData.value.rentals,
          backgroundColor: '#ffa726',
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
      },
    },
  })
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(amount)
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const d = new Date(dateStr)

  return d.toLocaleDateString()
}

function printReport() {
  const printContents = document.getElementById('printableReport').innerHTML
  const originalContents = document.body.innerHTML

  document.body.innerHTML = printContents
  window.print()
  document.body.innerHTML = originalContents
  window.location.reload()
}

function exportExcel() {
  if (!reportData.value.length) {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'warning',
      title: t('No data available to export'),
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    })

    return
  }

  const wsData = [
    headers.map(h => h.title),
    ...reportData.value.map(row => [
      row.period,
      row.total_sales,
      row.total_installment,
      row.total_rentals,
      row.grand_total,
    ]),
  ]

  const ws = XLSX.utils.aoa_to_sheet(wsData)
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'Report')
  XLSX.writeFile(wb, 'rental_report.xlsx')
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

<style>
@media print {
  .print-buttons {
    display: none !important;
  }
  .print-button {
    display: none !important;
  }

  /* Show the print-filters div when printing */
  .print-filters {
    display: block !important;
  }
  .print-hide {
    display: none !important;
  }
}
.line {
  display: flex;
  justify-content: flex-end;
  gap: 40px;
  margin-bottom: 30px;
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
  title: Daily Report
  layout: default
  subject: Auth
  active: 'report '
</route>
