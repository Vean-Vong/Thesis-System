<!-- eslint-disable import/extensions -->
<!-- eslint-disable import/no-unresolved -->
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
    .post('/users-list', {
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
    minWidth: '100px',
    maxWidth: '100px',
  },
  {
    title: t('User Name'),
    key: 'username',
    align: 'center',
    sortable: false,
    minWidth: '170px',
    maxWidth: '500px',
  },
  {
    title: t('Email'),
    key: 'email',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Role'),
    key: 'roles[0].name',
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
  router.push({ name: 'settings-form-user-detail-form', query: { id: item } })
}

const deleteCallback = item => {
  dialog.value = true
  delete_item.value = item
}

const editCallback = item => {
  router.push({
    name: 'settings-form-user-update-form',
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
    .post('users-delete', { id: delete_item.value })
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
    create-url="settings-form-user-create-form"
    :headers="headers"
    :items="items"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    :can-edit="user.can('edit_users')"
    :can-delete="user.can('delete_users')"
    :can-create="user.can('create_users')"
    btn-submit="CreateNew"
    :table-title="$t('List of Users')"
    cols="12"
    :loading="loading"
    @on-edit="editCallback"
    @on-update="updateCallback"
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
  title: User
  layout: default
  subject: Auth
  active: 'user'
</route>
