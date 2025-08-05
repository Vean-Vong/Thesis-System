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
const onPageChange = newPage => {
  meta.value.current_page = newPage
  initData()
}
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
    .get('/rentals', {
      params: {
        page: meta.value.current_page,
        limit: meta.value.per_page,
        search: search.value,
      },
    })
    .then(res => {
      const paginated = res.data.data
      console.log(paginated)
      items.value = paginated.data.map(rental => ({
        ...rental,
        date: formatDate(rental.date),
        price: `$${rental.price.toLocaleString()}`,
        deposit: rental.deposit && rental.deposit > 0 ? `$${Number(rental.deposit).toLocaleString()}` : '-',
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
  initData()
}

const headers = [
  {
    title: t('#'),
    key: 'no',
    align: 'left',
    sortable: false,
    minWidth: '10px',
    maxWidth: '100px',
  },
  {
    title: t('Invoice No.'),
    key: 'invoice_number',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Customer Name'),
    key: 'customer.name',
    align: 'center',
    sortable: false,
    minWidth: '100px',
    maxWidth: '500px',
  },
  {
    title: t('Rental Price'),
    key: 'price',
    align: 'center',
    minWidth: '100px',
    maxWidth: '500px',
    sortable: false,
  },
  {
    title: t('Deposit'),
    key: 'deposit',
    align: 'center',
    sortable: false,
    minWidth: '100px',
    maxWidth: '500px',
  },
  {
    title: t('Discount'),
    key: 'discount',
    align: 'center',
    sortable: false,
    minWidth: '100px',
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
    title: t('Duration'),
    key: 'duration',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },

  {
    title: t('Seller'),
    key: 'seller',
    align: 'center',
    sortable: false,
    minWidth: '190px',
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
  router.push({ name: 'rentals-payment', query: { id: item } })
}

const editCallback = item => {
  router.push({ name: 'rental-edit', query: { id: item } })
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
    .delete(`/rentals/${delete_item.value}`)
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
    create-url="rental-history"
    :headers="headers"
    :items="items"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    :can-view="user.can('rental_list')"
    :can-delete="user.can('rental_delete')"
    :can-create="user.can('rental_create')"
    :table-title="$t('List of Rentals')"
    btn-submit="CreateNew"
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
  <VRow
    cols="12"
    sm="6"
    class="justify-end"
  >
    <span class="mt-3"> {{ $t('Items per page') }} {{ meta?.current_page }} {{ $t('នៃ') }} {{ meta?.total }} </span>
    <VPagination
      v-model="meta.current_page"
      :length="meta.last_page"
      color="primary"
      circle
      total-visible="7"
      @update:model-value="onPageChange"
    />
  </VRow>
</template>

<route lang="yaml">
meta:
  title: Rentals
  layout: default
  subject: Auth
  active: 'rental'
</route>
