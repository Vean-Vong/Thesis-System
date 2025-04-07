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

const initData = () => {
  loading.value = true
  console.log('Data', items.value)
  api
    .get('/products', {
      page: meta?.current_page,
      limit: meta?.per_page,
      search: search.value,
    })
    .then(res => {
      items.value = res.data.data.data.map(product => ({
        ...product,
      }))
      meta.value = res.data.data.data.meta
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
    title: t('Image'),
    key: 'image',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Model'),
    key: 'model',
    align: 'center',
    sortable: false,
  },
  {
    title: t('Color'),
    key: 'color',
    align: 'center',
    sortable: false,
  },
  {
    title: t('filtration_stage'),
    key: 'filtration_stage',
    align: 'center',
    sortable: false,
  },
  {
    title: t('cold_water_tank_capacity'),
    key: 'cold_water_tank_capacity',
    align: 'center',
    sortable: false,
  },
  {
    title: t('hot_water_tank_capacity'),
    key: 'hot_water_tank_capacity',
    align: 'center',
    sortable: false,
  },
  {
    title: t('heating_capacity'),
    key: 'heating_capacity',
    align: 'center',
    sortable: false,
  },
  {
    title: t('cooling_capacity'),
    key: 'cooling_capacity',
    align: 'center',
    sortable: false,
  },
  {
    title: t('cold_power_consumption'),
    key: 'cold_power_consumption',
    align: 'center',
    sortable: false,
  },
  {
    title: t('hot_power_consumption'),
    key: 'hot_power_consumption',
    align: 'center',
    sortable: false,
  },
  {
    title: t('quantity'),
    key: 'quantity',
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
const editCallback = item => {
  router.push({ name: 'products-edit', query: { id: item } })
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
    .delete(`/products/${delete_item.value}`)
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
    create-url="products-create"
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
    :table-title="$t('List of Products')"
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
  title: product
  layout: default
  subject: Auth
  active: 'product'
</route>
