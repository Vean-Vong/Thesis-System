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
    .get('/employees', {
      page: meta?.current_page,
      limit: meta?.per_page,
      search: search.value,
    })
    .then(res => {
      items.value = res.data.data.data.map(item => ({
        ...item,
        date_of_birth: formatDate(item.date_of_birth),
        hire_date: formatDate(item.hire_date),
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
    title: t('headers.name'),
    key: 'name',
    align: 'center',
    sortable: false,
  },

  {
    title: t('Gender'),
    key: 'gender',
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
    title: t('Email'),
    key: 'email',
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
    title: t('Address'),
    key: 'address',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Date of Birth'),
    key: 'date_of_birth',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Hire Date'),
    key: 'hire_date',
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
  router.push({ name: 'settings-form-role-detail-form', query: { id: item } })
}

const editCallback = item => {
  router.push({ name: 'employees-edit', query: { id: item } })
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
    .delete(`/employees/${delete_item.value}`)
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
    create-url="employees-create"
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
    btn-submit="CreateNew"
    :table-title="$t('List of Employees')"
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
  title: Employee
  layout: default
  subject: Auth
  active: 'employee'
</route>
