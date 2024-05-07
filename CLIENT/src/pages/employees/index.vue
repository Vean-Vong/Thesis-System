<script setup>
import AppDataTable from '@/components/AppDataTable.vue'
import api from '@/plugins/utilites'
import router from '@/router'
import { onMounted } from 'vue'
import { useAuthStore } from '@/plugins/auth.module'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
const user = useAuthStore().user
const { t } = useI18n()
const items = ref([])
const loading = ref(false)

const search = ref(null)
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

const initData = () => {
  loading.value = true
  api
    .post('/employees-list', {
      page: meta?.current_page,
      limit: meta?.per_page,
      search: search.value,
    })
    .then(res => {
      items.value = res.data.data.data
      meta.value = res.data.data.meta
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  initData()
})

const headers = [
  {
    title: t('No'),
    key: 'no',
    align: 'left',
    sortable: false,
    minWidth: '10px',
    maxWidth: '20px',
  },
  {
    title: t('Code'),
    key: 'code',
    align: 'center',
    sortable: false,
    minWidth: '20px',
    maxWidth: '40px',
  },
  {
    title: t('Khmer Name'),
    key: 'khmer_name',
    align: 'center',
    sortable: false,
  },
  {
    title: t('latin_name'),
    key: 'latin_name',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Sex'),
    key: 'sex_text',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Phone'),
    key: 'phone',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Position'),
    key: 'position',
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
  router.push({ name: 'employees-show', query: { id: item } })
}

const deleteCallback = item => {
  dialog.value = true
  delete_item.value = item
}

const editCallback = item => {
  router.push({
    name: 'employees-edit',
    query: { uuid: item },
  })
}

const updateCallback = item => {
  meta.current_page = item.page
  meta.per_page = item.limit
  initData()
}

const onSearch = () => {
  initData()
}

const cancelCallback = () => {
  dialog.value = false
  delete_item.value = null
}

const confirmDeleteCallback = () => {
  deleting.value = true
  api
    .post('employees-delete', { id: delete_item.value })
    .then(res => {
      if (res.status == 200) {
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
    create-url="employees-create"
    :headers="headers"
    :items="items"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    :can-edit="user.can('edit employees')"
    :can-view="user.can('view employees')"
    :can-delete="user.can('delete employees')"
    :can-create="user.can('create employees')"
    btn-submit="CreateNew"
    :table-title="$t('List of Employee')"
    cols="12"
    :loading="loading"
    @on-edit="editCallback"
    @on-view="viewCallback"
    @on-update="updateCallback"
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
  title: Employee
  layout: default
  subject: Auth
  active: 'employee'
</route>
