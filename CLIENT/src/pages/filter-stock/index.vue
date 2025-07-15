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
  const d = new Date(date)
  const day = d.getDate()
  const month = d.toLocaleString('en-US', { month: 'long' })
  const year = d.getFullYear()
  // eslint-disable-next-line newline-before-return
  return `${day} ${month}, ${year}`
}

const initData = () => {
  loading.value = true
  api
    .get('/filters', {
      page: meta?.current_page,
      limit: meta?.per_page,
      search: search.value,
    })
    .then(res => {
      items.value = res.data.data.data.map(item => ({
        ...item,
        date: formatDate(item.date), // Format date field if it exists
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
    minWidth: '100px',
    maxWidth: '100px',
  },
  {
    title: t('Sediment'),
    key: 'sediment',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },

  {
    title: t('Pre-Carbon'),
    key: 'pre_carbon',
    align: 'center',
    minWidth: '150px',
    maxWidth: '500px',
    sortable: false,
  },
  {
    title: t('UF-Membrane'),
    key: 'uf_membrane',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Post-Carbon'),
    key: 'post_carbon',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Date'),
    key: 'date',
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
  },
]

const viewCallback = item => {
  router.push({ name: 'filter-stock-show', query: { id: item } })
}

const editCallback = item => {
  router.push({ name: 'filter-stock-edit', query: { id: item } })
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
    .delete(`/filters/${delete_item.value}`)
    .then(res => {
      if (res.status === 200) {
        initData()
      }
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
    create-url="filter-stock-create"
    :headers="headers"
    :items="items"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    :can-edit="user.can('filter_stock_edit')"
    :can-delete="user.can('filter_stock_delete')"
    :can-view="user.can('filter_stock_list')"
    :can-create="user.can('filter_stock_create')"
    btn-submit="CreateNew"
    :table-title="$t('List of Filter')"
    :loading="loading"
    @on-edit="editCallback"
    @on-create="createCallback"
    @on-delete="deleteCallback"
    @on-view="viewCallback"
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
  title: Filter Stock
  layout: default
  subject: Auth
  active: 'filter-stock'
</route>
