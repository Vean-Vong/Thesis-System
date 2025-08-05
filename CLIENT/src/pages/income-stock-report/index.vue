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
        <h2>{{ $t('Total Stock Report') }}</h2>
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

    <!-- Date Filters -->
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
      <template #bottom>
        <VDivider class="my-2" />
        <tbody class="d-flex justify-end">
          <tr class="line">
            <td class="d-flex">
              <h3>{{ $t('Grand_Total') }}:</h3>
              <h3 class="ps-3">{{ grandTotal }}</h3>
            </td>
          </tr>
        </tbody>
      </template>
    </VDataTable>
  </VContainer>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import Swal from 'sweetalert2'
import * as XLSX from 'xlsx'
// eslint-disable-next-line import/no-unresolved, import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-unresolved
import Logo from '@/assets/images/logo.png'

const { t } = useI18n()

// Filters: only date range
const today = new Date()
const oneWeekAgo = new Date()
oneWeekAgo.setDate(today.getDate() - 7)

const filters = ref({
  start_date: oneWeekAgo.toISOString().substring(0, 10),
  end_date: today.toISOString().substring(0, 10),
})

const headers = ref([
  { title: t('Date'), key: 'date' },
  { title: t('Model'), key: 'model' },
  { title: t('Quantity'), key: 'quantity' },
  { title: t('Supplier'), key: 'supplier' },
])

const reportData = ref([])
const loading = ref(false)

const grandTotal = computed(() => reportData.value.reduce((sum, item) => sum + (item.quantity ?? 0), 0))

async function fetchReport() {
  loading.value = true
  try {
    const res = await api.get('/stocks-report', {
      params: filters.value,
    })

    if (res.status === 200 && res.data.status === 200) {
      reportData.value = res.data.data.map(item => ({
        date: item.date,
        model: item.model,
        quantity: item.quantity,
        supplier: item.supplier,
      }))
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

  const headerTitles = headers.value.map(h => h.title)
  const rows = reportData.value.map(row => headers.value.map(h => row[h.key] ?? ''))
  const ws = XLSX.utils.aoa_to_sheet([headerTitles, ...rows])
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'Stock Report')
  XLSX.writeFile(wb, 'stock_report.xlsx')
}

onMounted(() => {
  fetchReport()
})
</script>

<style>
@media print {
  .print-buttons,
  .print-button,
  .print-hide {
    display: none !important;
  }
}
.www {
  margin-top: -20px;
}
.print-button {
  display: flex;
  justify-content: flex-end;
  margin-top: -20px;
}
</style>

<route lang="yaml">
meta:
  title: Stock Report
  layout: default
  subject: Auth
  active: 'stock_report'
</route>
