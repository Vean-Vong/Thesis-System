<!-- eslint-disable vue/valid-v-slot -->
<template>
  <VContainer id="printableReport">
    <!-- Header -->
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
        <h2>{{ $t('Total Install Report') }}</h2>
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
        @click="exportExcel"
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

    <!-- Filters for printing -->
    <div
      class="print-filters mt-4 mb-4"
      style="display: none"
    >
      <p>
        <strong>{{ $t('Start Date') }}:</strong> {{ formatDate(filters.start_date) || '-' }}
        <strong>{{ $t('End Date') }}:</strong> {{ formatDate(filters.end_date) || '-' }}
      </p>
    </div>

    <!-- Generate Report -->
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
      style="max-width: 900px"
    >
      <template #item.total_installment="{ item }">
        {{ formatCurrency(item.total_installment) }}
      </template>

      <template #item.grand_total="{ item }">
        {{ formatCurrency(item.grand_total) }}
      </template>

      <template #bottom>
        <VDivider class="my-2" />
        <tbody class="d-flex justify-end">
          <tr class="line mt-3">
            <td>
              <h3>{{ $t('Grand_Total') }} :</h3>
            </td>
            <td>
              <h3>{{ formatCurrency(grandSubtotal) }}</h3>
            </td>
          </tr>
        </tbody>
      </template>
    </VDataTable>

    <!-- Chart -->
    <VCard
      v-if="chartData.labels.length"
      class="mt-6 print-hide"
    >
      <VCardTitle>{{ $t('Install Chart') }}</VCardTitle>
      <VCardText>
        <div class="chart-wrapper-rental-sale-install">
          <canvas id="reportChart" />
        </div>
      </VCardText>
    </VCard>
  </VContainer>
</template>

<script setup>
import Swal from 'sweetalert2'
import * as XLSX from 'xlsx'
import { ref, computed, nextTick, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import Chart from 'chart.js/auto'
// eslint-disable-next-line import/no-unresolved
import Logo from '@/assets/images/logo.png'

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

const headers = [
  { title: t('Date'), key: 'period', align: 'start' },
  { title: t('Installment'), key: 'total_installment', align: 'start' },
  { title: t('Grand Total'), key: 'total_installment', align: 'start' },
]

const groupByOptions = ['day', 'month', 'year']

const reportData = ref([])
const loading = ref(false)

const chartData = ref({ labels: [], rentals: [] })
let chartInstance = null

const grandSubtotal = computed(() => reportData.value.reduce((sum, item) => sum + (item.total_installment || 0), 0))

async function fetchReport() {
  loading.value = true
  try {
    const res = await api.get('/installment-report', { params: filters.value })
    if (res.status === 200 && res.data.status === 200) {
      const raw = res.data.data
      reportData.value = Object.keys(raw).map(period => ({
        period,
        total_installment: Number(raw[period].total_installment) || 0,
        grand_total: Number(raw[period].grand_total) || 0,
      }))
      prepareChartData()
    } else {
      alert(t('Failed to fetch report data'))
    }
  } catch (e) {
    console.error(e)
    alert(t('Failed to fetch report data'))
  } finally {
    loading.value = false
  }
}

function prepareChartData() {
  chartData.value.labels = reportData.value.map(item => item.period)
  chartData.value.rentals = reportData.value.map(item => item.total_installment)
  renderChart()
}

async function renderChart() {
  await nextTick()
  const ctx = document.getElementById('reportChart')
  if (!ctx) return
  if (chartInstance) chartInstance.destroy()
  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: chartData.value.labels,
      datasets: [
        {
          label: t('Total Installments'),
          data: chartData.value.rentals,
          backgroundColor: '#42a5f5',
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top' } },
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

  return new Date(dateStr).toLocaleDateString()
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
    ...reportData.value.map(row => [row.period, row.total_installment, row.grand_total]),
  ]
  const ws = XLSX.utils.aoa_to_sheet(wsData)
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'Rental Report')
  XLSX.writeFile(wb, 'rental_report.xlsx')
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
  title: Rental Report
  layout: default
  subject: Auth
  active: 'rental_report'
</route>
