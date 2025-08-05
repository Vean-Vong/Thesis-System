<!-- eslint-disable import/extensions -->
<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

// eslint-disable-next-line import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-unresolved
import { useAuthStore } from '@/plugins/auth.module'
import { useI18n } from 'vue-i18n'

const router = useRouter()
const user = useAuthStore().user
const { t } = useI18n()

const items = ref([])
const search = ref(null)
const loading = ref(false)
const syncing = ref(false)
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

const pad = n => (n < 10 ? `0${n}` : n)

const formatDate = date => {
  if (!date) return ''
  const d = new Date(date)

  return `${pad(d.getDate())}-${pad(d.getMonth() + 1)}-${d.getFullYear()}`
}

const initData = () => {
  loading.value = true
  api
    .get('/installments', {
      params: {
        page: meta.value.current_page,
        limit: meta.value.per_page,
        search: search.value?.trim() || '',
      },
    })
    // eslint-disable-next-line sonarjs/cognitive-complexity
    .then(res => {
      const data = res.data.data
      console.log(data)
      items.value = (data.data || []).map((item, index) => ({
        ...item,
        no: (data.current_page - 1) * data.per_page + index + 1,
        sale_models: item.sale?.products?.map(p => p.name).join(', ') || item.sale?.model || '',
        contract_type: item.sale?.contract_type || '',
        customer_name: item.customer?.name || '',
        seller_name: item.seller?.username || '', // fetch Username from User
        sale_seller: item.sale?.seller || '-', // fetch Username from sale
        duration: item.sale?.duration || '',
        invoice_number: item.sale?.invoice_number || '',
        sale_date: formatDate(item.sale_date),
        deposit: item.deposit ? `$${item.deposit.toLocaleString()}` : '-',

        monthly_fee: (() => {
          const products = item.sale?.products || []
          if (products.length > 0 && products[0].install_price != null) {
            return `$${Number(products[0].install_price).toLocaleString()}`
          }

          return '-'
        })(),

        total_price: item.total_price ? `$${item.total_price.toLocaleString()}` : '-',
        paid_amount: item.paid_amount ? `$${item.paid_amount.toLocaleString()}` : '-',

        balance: (() => {
          const products = item.sale?.products || []
          if (products.length === 0) return '$0'

          const firstInstallPrice = Number(products[0].install_price) || 0
          const duration = Number(item.sale?.duration) || 36
          const totalExpected = firstInstallPrice * duration
          const paid = Number(item.paid_amount) || 0
          const remaining = totalExpected - paid

          return remaining > 0 ? `$${remaining.toLocaleString()}` : '$0'
        })(),
      }))

      meta.value = {
        current_page: data.current_page,
        per_page: data.per_page,
        last_page: data.last_page,
        total: data.total,
        from: (data.current_page - 1) * data.per_page + 1,
        to: (data.current_page - 1) * data.per_page + items.value.length,
      }
    })
    .catch(() => {
      items.value = []
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => initData())

const onSearch = () => {
  meta.value.current_page = 1
  initData()
}

const syncInstallments = () => {
  syncing.value = true
  api
    .post('/installments/sync')
    .then(res => {
      Swal.fire('✅ Success', res.data.message || 'Installments synced successfully.', 'success')
      initData()
    })
    .catch(err => {
      Swal.fire('❌ Error', err.response?.data?.message || 'Failed to sync installments.', 'error')
    })
    .finally(() => {
      syncing.value = false
    })
}
const edit = item => {
  const id = typeof item === 'object' ? item.id : item
  router.push({ name: 'installment-edit', query: { id } })
}

const ViewList = item => {
  const id = typeof item === 'object' ? item.id : item
  router.push({ name: 'installment-view', query: { id } })
}

const deleteCallback = item => {
  dialog.value = true
  delete_item.value = typeof item === 'object' ? item.id : item
}

const confirmDeleteCallback = () => {
  if (!delete_item.value) return

  deleting.value = true
  api
    .delete(`/installments/${delete_item.value}`)
    .then(() => {
      initData()
    })
    .finally(() => {
      deleting.value = false
      dialog.value = false
      delete_item.value = null
    })
}

const cancelCallback = () => {
  dialog.value = false
  delete_item.value = null
}

const headers = [
  { title: t('#'), key: 'no', align: 'left', sortable: false, minWidth: '10px', maxWidth: '30px' },

  {
    title: t('Invoice No.'),
    key: 'invoice_number',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  {
    title: t('Customer'),
    key: 'customer_name',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  { title: t('Deposit'), key: 'deposit', align: 'center', sortable: false, minWidth: '100px', maxWidth: '100px' },
  { title: t('Date'), key: 'sale_date', align: 'center', sortable: false, minWidth: '130px', maxWidth: '100px' },

  {
    title: t('Price'),
    key: 'total_price',
    align: 'center',
    sortable: false,
    minWidth: '100px',
    maxWidth: '100px',
  },

  {
    title: t('Monthly Fee'),
    key: 'monthly_fee',
    align: 'center',
    sortable: false,
    minWidth: '120px',
    maxWidth: '120px',
  },
  {
    title: t('Already Paid'),
    key: 'paid_amount',
    align: 'center',
    sortable: false,
    minWidth: '120px',
    maxWidth: '120px',
  },
  {
    title: t('Remaining Balance'),
    key: 'balance',
    align: 'center',
    sortable: false,
    minWidth: '150px',
    maxWidth: '500px',
  },
  { title: t('Duration'), key: 'duration', align: 'center', sortable: false, minWidth: '100px', maxWidth: '100px' },
  { title: t('Seller'), key: 'sale_seller', align: 'center', sortable: false, minWidth: '190px', maxWidth: '500px' },
  { title: t('Actions'), key: 'actions', align: 'center', sortable: false, minWidth: '200px', maxWidth: '500px' },
]
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
    create-url="installment-create"
    :headers="headers"
    :items="items"
    :items-per-page="meta.per_page"
    :items-length="meta.total"
    :from="meta.from"
    :current-page="meta.current_page"
    :to="meta.to"
    :can-edit="user.can('install_edit')"
    :can-delete="user.can('install_delete')"
    :can-create="user.can('install_create')"
    :can-view="user.can('install_list')"
    :can-payment="user.can('payments')"
    btn-submit="Create New"
    :table-title="t('Installment')"
    :loading="loading"
    @on-edit="edit"
    @on-delete="deleteCallback"
    @on-view="ViewList"
  >
    <template #forFilter>
      <VRow dense>
        <VCol
          cols="8"
          md="3"
        >
          <AppTextField
            v-model="search"
            :placeholder="t('Search')"
            @keyup.enter="onSearch"
          />
        </VCol>
        <VCol
          cols="4"
          md="2"
        >
          <AppSearchButton
            :title="t('Search')"
            show-icon
            @click="onSearch"
          />
        </VCol>
        <VCol
          cols="12"
          md="3"
          class="text-right"
        >
          <VBtn
            color="primary"
            :loading="syncing"
            :disabled="syncing"
            @click="syncInstallments"
          >
            🔄 {{ t('Sync Installments') }}
          </VBtn>
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
  title: Installment
  layout: default
  subject: Auth
  active: 'installment'
</route>
