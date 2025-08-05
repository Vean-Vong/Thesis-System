<!-- eslint-disable indent -->
<!-- eslint-disable import/extensions -->
<!-- eslint-disable import/no-unresolved -->
<script setup>
import { ref, computed, onMounted } from 'vue'
import AppDataTable from '@/components/AppDataTable.vue'
import api from '@/plugins/utilites'
import router from '@/router'
import { useAuthStore } from '@/plugins/auth.module'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
import { VBtn, VRow, VCol } from 'vuetify/lib/components/index.mjs'

const user = useAuthStore().user
const { t } = useI18n()

const items = ref([])
const search = ref(null)
const loading = ref(false)
const delete_item = ref(null)
const deleting = ref(false)
const dialog = ref(false)
const printRef = ref(null)

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
        switch (utility_expense.type) {
          case 'Electricity':
            unit = 'kWh'
            break
          case 'Water':
            unit = 'm³'
            break
          case 'Garbage':
            unit = 'Kg'
            break
        }

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
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  initData()
})

const onSearch = () => {
  initData()
}

const headers = [
  {
    title: t('No'),
    key: 'no',
    align: 'left',
    sortable: false,
    minWidth: '100px',
    maxWidth: '100px',
  },
  {
    title: t('Type'),
    key: 'type',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Bill Date'),
    key: 'bill_date',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Usage Amount'),
    key: 'usage_amount',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Cost'),
    key: 'cost',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Unit Rate'),
    key: 'unit_rate',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Actions'),
    key: 'actions',
    align: 'center',
    sortable: false,
    class: 'actions',
  },
]

const viewCallback = item => {
  router.push({ name: 'utility_expenses-show', query: { id: item } })
}

const viewInvoice = item => {
  router.push({ name: 'utility_expenses-invoice', query: { id: item } })
}

const editCallback = item => {
  router.push({ name: 'utility_expenses-edit', query: { id: item } })
}

const deleteCallback = item => {
  dialog.value = true
  delete_item.value = item
}

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
</script>

<template>
  <ConfirmDialog
    v-model="dialog"
    :deleting="deleting"
    @on-cancel="cancelCallback"
    @on-confirm-delete="confirmDeleteCallback"
  />

  <!-- Wrap table and total cost in ref container for printing -->
  <div ref="printRef">
    <AppDataTable
      cols="12"
      create-url="utility_expenses-create"
      :headers="headers"
      :items="items"
      :items-per-page="meta?.per_page"
      :items-length="meta?.total"
      :from="meta?.from"
      :current-page="meta?.current_page"
      :to="meta?.to"
      :can-edit="user.can('utility_expenses_edit')"
      :can-delete="user.can('utility_expenses_delete')"
      :can-view="user.can('utility_expenses_list')"
      :can-create="user.can('utility_expenses_create')"
      :table-title="$t('List of utility_expenses')"
      btn-submit="CreateNew"
      :loading="loading"
      @on-edit="editCallback"
      @on-create="initData"
      @on-delete="deleteCallback"
      @on-view="viewCallback"
    >
      <template #forFilter>
        <VRow
          class="justify-start"
          dense
        >
          <VCol
            cols="8"
            md="3"
          >
            <AppTextField
              v-model="search"
              :placeholder="$t('Search')"
              @keyup.enter="onSearch"
            />
          </VCol>
          <VCol
            cols="4"
            md="2"
          >
            <AppSearchButton
              :title="$t('Search')"
              :show-icon="1"
              @click="onSearch"
            />
          </VCol>
        </VRow>
        <VBtn
          color="primary"
          @click="viewInvoice"
        >
          {{ t('Invoice') }}
        </VBtn>
      </template>
    </AppDataTable>
  </div>
</template>

<route lang="yaml">
meta:
  title: Utility Expenses
  layout: default
  subject: Auth
  active: 'utility_expenses '
</route>
