<script setup>
import { ref, computed, onMounted } from 'vue'
// eslint-disable-next-line import/no-unresolved
import AppDataTable from '@/components/AppDataTable.vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'
// eslint-disable-next-line import/no-unresolved
import { useAuthStore } from '@/plugins/auth.module'
// eslint-disable-next-line import/no-unresolved
import ConfirmDialog from '@/components/ConfirmDialog.vue'
import { useI18n } from 'vue-i18n'

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

// Computed property to add 'no' and format 'total_stock'
const displayItems = computed(() =>
  items.value.map((item, index) => ({
    ...item,
    no: meta.value.from + index,
    total_stock: item.total_stock && item.total_stock > 0 ? item.total_stock : 'អស់ស្តុក',
  })),
)

const initData = () => {
  loading.value = true
  api
    .get('/products', {
      params: {
        page: meta.value.current_page,
        limit: meta.value.per_page,
        search: search.value,
      },
    })
    .then(res => {
      const paginated = res.data.data
      items.value = paginated.data.map(item => ({
        ...item,
        price: `$${item.price}`,
      }))
      meta.value = {
        current_page: paginated.current_page,
        from: paginated.from,
        last_page: paginated.last_page,
        per_page: paginated.per_page,
        to: paginated.to,
        total: paginated.total,
      }
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  initData()
})

const onSearch = () => {
  meta.value.current_page = 1
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
    title: t('Model'),
    key: 'model',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Color'),
    key: 'colors',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Filter'),
    key: 'filtration_stage',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Quantity'),
    key: 'total_stock',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Price'),
    key: 'price',
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

const editCallback = item => {
  router.push({ name: 'products-edit', query: { id: item } })
}

const viewCallback = item => {
  router.push({ name: 'products-show', query: { id: item } })
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
    :items="displayItems"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    :can-edit="user.can('product_edit')"
    :can-view="user.can('product_list')"
    :can-delete="user.can('product_delete')"
    :can-create="user.can('product_create')"
    :table-title="$t('List of Products')"
    btn-submit="CreateNew"
    :loading="loading"
    @on-edit="editCallback"
    @on-create="createCallback"
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
  title: product
  layout: default
  subject: Auth
  active: 'product'
</route>
