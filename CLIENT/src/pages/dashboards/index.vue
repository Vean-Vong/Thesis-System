<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import { useI18n } from 'vue-i18n'
const router = useRouter()
const { t } = useI18n()
const price = ref(0) // Store price

onMounted(async () => {
  try {
    const response = await api.post('summary')
    const data = response.data
    console.log('SUMMARY DATA:', data)

    statistics.value[0].stats = data.totalSalesAmount
    statistics.value[1].stats = data.totalUtility_expenses
    statistics.value[2].stats = data.sales
    statistics.value[3].stats = data.employees
    statistics.value[4].stats = data.customers
    statistics.value[5].stats = data.users
    statistics.value[6].stats = data.products
    statistics.value[7].stats = data.rental
    statistics.value[8].stats = data.productStock
    statistics.value[9].stats = data.filter
  } catch (error) {
    console.error('Error fetching summary:', error)
  }
})

const statistics = ref([
  {
    title: 'Total Earnings',
    stats: 0,
    icon: 'mdi-cash-multiple',
    color: '#3b28cc',
    name: 'totalSalesAmount',
    i18nKey: 'Total Earnings',
  },
  {
    title: 'Utility Expenses',
    stats: 0,
    icon: 'mdi-wallet',
    color: '#3b28cc',
    name: 'totalUtility_expenses',
    to: '/utility_expenses',
    i18nKey: 'Utility Expenses',
  },
  {
    title: 'Sale Total',
    stats: 0,
    icon: 'mdi-cart-outline',
    color: '#3b28cc',
    name: 'sales',
    to: '/sales',
    i18nKey: 'Sale',
  },
  {
    title: 'Employee Total',
    stats: 0,
    icon: 'mdi-account-tie',
    color: 'primary',
    name: 'employees',
    to: '/employees',
    i18nKey: 'Employee',
  },
  {
    title: 'Customer Total',
    stats: 0,
    icon: 'mdi-account-group',
    color: '#3b28cc',
    name: 'customers',
    to: '/customers',
    i18nKey: 'Customer',
  },
  {
    title: 'User Total',
    stats: 0,
    icon: 'mdi-account-star',
    color: '#3b28cc',
    name: 'users',
    to: 'settings/user-settings',
    i18nKey: 'total_users',
  },
  {
    title: 'Product',
    stats: 0,
    icon: 'mdi-package-variant',
    color: '#3b28cc',
    name: 'product',
    to: '/products',
    i18nKey: 'Product',
  },
  {
    title: 'Rental Total',
    stats: 0,
    icon: 'mdi-cart-arrow-down',
    color: 'primary',
    name: 'rental',
    to: '/rental',
    i18nKey: 'Rental',
  },
  {
    title: 'Product in Stock',
    stats: 0,
    icon: 'mdi-warehouse',
    color: '#3b28cc',
    name: 'productStock',
    to: '/product-stock',
    i18nKey: 'Product in Stock',
  },
  {
    title: 'Filters Stock',
    stats: price.value,
    icon: 'mdi-filter-menu',
    color: '#3b28cc',
    name: 'filter',
    to: '/filter-stock',
    i18nKey: 'Filter in Stock',
  },
])

const series = ref([
  {
    name: 'Inflation',
    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  },
])

const chartOptions = ref({
  chart: {
    height: 350,
    type: 'bar',
  },
  plotOptions: {
    bar: {
      borderRadius: 10,
      dataLabels: {
        position: 'top', // top, center, bottom
      },
    },
  },
  dataLabels: {
    enabled: true,
    formatter: function (val) {
      return val
    },
    offsetY: -20,
    style: {
      fontSize: '12px',
      colors: ['#304758'],
    },
  },
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    position: 'top',
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        },
      },
    },
    tooltip: {
      enabled: true,
    },
  },
  yaxis: {
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: true,
    },
    labels: {
      show: false,
      formatter: function (val) {
        return val
      },
    },
  },
  title: {
    text: t('Sales in This Year'),
    floating: true,
    show: true,
    offsetY: 330,
    align: 'center',
    style: {
      color: '#444',
    },
  },
})

const go = to => {
  router.push(to)
}
</script>

<template>
  <VCard>
    <VCardItem>
      <VCardTitle class="text-xl text-primary">
        {{ $t('total_data_table') }}
      </VCardTitle>
    </VCardItem>
    <VCardText class="my-">
      <VRow>
        <VCol
          v-for="item in statistics"
          :key="item.title"
          cols="12"
          sm="6"
          md="4"
          lg="3"
        >
          <VCard
            :class="stat - card"
            :color="item.color"
            class="d-flex align-center mb-1 py-5 rounded-2"
            dark
            elevation="20"
            @click="go(item.to)"
          >
            <VAvatar
              :color="item.color"
              rounded
              size="50"
              class="elevation-1 me-2 ms-2"
            >
              <VIcon
                size="30"
                :icon="item.icon"
              />
            </VAvatar>
            <VDivider
              vertical
              class="text-white"
              :thickness="1"
            />
            <div class="d-flex flex-column mx-4">
              <div class="">
                {{ $t(item.i18nKey) }}
              </div>
            </div>
            <span class="text-2xl font-weight-medium text-white ms-auto mx-5">
              {{
                item.name === 'totalSalesAmount' || item.name === 'totalUtility_expenses'
                  ? '$ ' + Number(item.stats).toLocaleString()
                  : item.stats.toLocaleString()
              }}
            </span>
          </VCard>
        </VCol>
      </VRow>

      <div class="mt-12">
        <VueApexCharts
          type="bar"
          height="350"
          :options="chartOptions"
          :series="series"
        />
      </div>
    </VCardText>
  </VCard>
</template>

<script>
import VueApexCharts from 'vue3-apexcharts'
export default {
  components: {
    VueApexCharts,
  },
}
</script>

<style scoped>
.link:hover {
  cursor: pointer;
}
.stat-card {
  background: linear-gradient(to bottom right, #42a5f5, #0d47a1);
}
</style>

<route lang="yaml">
meta:
  title: Dashboard
  layout: default
  subject: Auth
  active: 'dashboard'
</route>
