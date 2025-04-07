<!-- eslint-disable import/extensions -->
<!-- eslint-disable import/no-unresolved -->
<script setup>
import AppDataTable from '@/components/AppDataTable.vue'
import api from '@/plugins/utilites'
import router from '@/router'
import { useAuthStore } from '@/plugins/auth.module'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
const user = useAuthStore().user
const { t } = useI18n()
const items = ref([])
const search = ref(null)
const loading = ref(false)
const delete_item = ref(null)
const deleting = ref(false)
const dialog = ref(false)

const meta = ref({
  current_page: 1,
  from: 1,
  last_page: 1,
  per_page: 15,
  to: 1,
  total: 0,
})
const formatDate = date => {
  if (!date) return ''
  // eslint-disable-next-line newline-before-return
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}
const initData = () => {
  loading.value = true
  api
    .get('/sales', {
      page: meta?.current_page,
      limit: meta?.per_page,
      search: search.value,
    })
    .then(res => {
      items.value = res.data.data.data.map(sale => ({
        ...sale,
        price: `$${sale.price.toLocaleString()}`, // Add $ symbol
        discount: `${sale.discount}%`, // Add % symbol
        date: formatDate(sale.date),
      }))
      meta.value = res.data.data.meta
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
  },

  {
    title: t('Model'),
    key: 'model',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Price'),
    key: 'price',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Discount'),
    key: 'discount',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Date'),
    key: 'date',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Duration'),
    key: 'duration',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Warranty'),
    key: 'warranty',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Seller'),
    key: 'seller',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Contract Type'),
    key: 'contract_type',
    align: 'center',
    sortable: false,
  },

  {
    title: t('Actions'),
    key: 'actions',
    align: 'center',
    sortable: false,
  },
]

const viewCallback = item => {
  router.push({ name: 'sales-show', query: { id: item } })
}

const editCallback = item => {
  router.push({ name: 'sales-edit', query: { id: item } })
}

// const updateCallback = item => {
//   meta.current_page = item.page
//   meta.per_page = item.limit
//   initData()
// }
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
    .delete(`/sales/${delete_item.value}`)
    .then(res => {
      if (res.status === 200) {
        initData()
      }
    })
    .catch(error => {
      console.error('Delete request failed:', error)
    })
    .finally(() => {
      deleting.value = false
      delete_item.value = null
      dialog.value = false
    })
}
</script>

<template>
  <ConfirmDialog
    v-model="dialog"
    :deleting="deleting"
    @on-cancel="cancelCallback"
    @on-confirm-delete="confirmDeleteCallback"
  />
  <AppDataTable
    cols="12"
    create-url="sales-create"
    :headers="headers"
    :items="items"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    :can-edit="user.can('edit_roles')"
    :can-delete="user.can('delete_roles')"
    :can-create="user.can('create_roles')"
    :table-title="$t('List of Sales')"
    btn-submit="CreateNew"
    :loading="loading"
    @on-edit="editCallback"
    @on-create="createCallback"
    @on-delete="deleteCallback"
  >
    <template #forFilter>
      <!-- <p>Search and Filter</p> -->
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
    </template>
  </AppDataTable>
</template>

<route lang="yaml">
meta:
  title: Sale
  layout: default
  subject: Auth
  active: 'sale'
</route>

