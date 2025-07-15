<!-- eslint-disable import/extensions -->
<!-- eslint-disable import/no-unresolved -->
<script setup>
import AppDataTable from '@/components/AppDataTable.vue'
import api from '@/plugins/utilites'
import router from '@/router'
import { useAuthStore } from '@/plugins/auth.module'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const user = useAuthStore().user
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
    .get('/customers', {
      params: {
        page: meta.value.current_page,
        limit: meta.value.per_page,
        search: search.value,
      },
    })
    .then(res => {
      const response = res.data.data
      items.value = response.data.map(item => ({
        ...item,
        date: formatDate(item.date),
      }))
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  initData()
})

const headers = [
  { title: t('No'), key: 'no', align: 'left', sortable: false, minWidth: '100px' },
  { title: t('headers.name'), key: 'name', align: 'center', sortable: false, minWidth: '150px', maxWidth: '500px' },
  { title: t('Address'), key: 'address', align: 'center', sortable: false, minWidth: '200px', maxWidth: '500px' },
  { title: t('Phone'), key: 'phone', align: 'center', sortable: false, minWidth: '200px', maxWidth: '500px' },
  { title: t('Date'), key: 'date', align: 'center', sortable: false, minWidth: '200px', maxWidth: '500px' },
  { title: t('Job'), key: 'job', align: 'center', sortable: false, minWidth: '200px', maxWidth: '500px' },
  { title: t('Actions'), key: 'actions', align: 'center', sortable: false },
]

const viewCallback = item => {
  router.push({ name: 'customers-show', query: { id: item } })
}
const onSearch = () => {
  initData()
}
const editCallback = item => {
  router.push({ name: 'customers-edit', query: { id: item } })
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
    .delete(`/customers/${delete_item.value}`)
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
    create-url="customers-create"
    :headers="headers"
    :items="items"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    :can-edit="user.can('customer_edit')"
    :can-delete="user.can('customer_delete')"
    :can-create="user.can('customer_create')"
    :can-view="user.can('customer_list')"
    :table-title="$t('List of Customers')"
    btn-submit="CreateNew"
    :loading="loading"
    @on-edit="editCallback"
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
    </template>
  </AppDataTable>
</template>

<route lang="yaml">
meta:
  title: Customer
  layout: default
  active: 'customer'
</route>
