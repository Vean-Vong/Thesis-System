<!-- eslint-disable import/no-unresolved -->
<script setup>
import ChartJsHorizontalPieChart from '@/views/charts/ChartJsHorizontalPieChart.vue'
import AppCardinfo from '@/components/AppCardInfo.vue'
// eslint-disable-next-line import/extensions
import router from '@/router'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'

const chartJsCustomColors = {
  white: '#fff',
  yellow: '#ffe802',
  primary: '#836af9',
  areaChartBlue: '#2c9aff',
  barChartYellow: '#ffcf5c',
  polarChartGrey: '#4f5d70',
  polarChartInfo: '#299aff',
  lineChartYellow: '#d4e157',
  polarChartGreen: '#28dac6',
  lineChartPrimary: '#9e69fd',
  lineChartWarning: '#ff9800',
  horizontalBarInfo: '#26c6da',
  polarChartWarning: '#ff8131',
  scatterChartGreen: '#28c76f',
  warningShade: '#ffbd1f',
  areaChartBlueLight: '#84d0ff',
  areaChartGreyLight: '#edf1f4',
  scatterChartWarning: '#ff9f43',
}

const items = ref([
  {
    id: 1,
    icon: 'mdi-face-agent',
    Name: 'Receptionist',
    backGroundColor: 'bg-primary',
    count: 0,
    type: 'Total',
    link: '/transactions/list1',
    current_stage: 'receptionist',
  },
  {
    id: 2,
    icon: 'mdi-hospital-box-outline',
    Name: 'Doctor',
    backGroundColor: 'bg-success',
    count: 0,
    type: 'Total',
    link: '/transactions/list2',
    current_stage: 'doctor',
  },
  {
    id: 3,
    icon: 'mdi-hotel',
    Name: 'Staying',
    backGroundColor: 'bg-warning',
    count: 0,
    type: 'Total',
    link: '/transactions/list3',
    current_stage: 'stay',
  },
  {
    id: 4,
    icon: 'mdi-human-handsup',
    Name: 'Finish',
    backGroundColor: 'bg-error',
    count: 0,
    type: 'Total',
    link: '/transactions/list4',
    current_stage: 'finish',
  },
  {
    id: 5,
    icon: 'mdi-magnify',
    Name: 'Search',
    backGroundColor: 'bg-primary',
    count: 0,
    type: 'Total',
    link: '/transactions/list',
    current_stage: 'all',
  },
])

const viewList = link => {
  router.push(link)
}

onMounted(() => {
  api.post('transaction-summary').then(res => {
    if (res.status == 200) {
      items.value.forEach(element => {
        element.count = res.data.data[element.current_stage]
      })
    }
  })
})
</script>

<template>
  <VRow>
    <AppCardinfo
      v-for="ret in items"
      :key="ret.id"
      :item="ret"
      @click="viewList(ret.link)"
    />
  </VRow>
  <VRow>
    <VCol
      cols="12"
      md="12"
    >
      <VCard class="pa-4">
        <VCardTitle class="text-center">Paitions This month</VCardTitle>
        <VCardItem>
          <ChartJsHorizontalPieChart :colors="chartJsCustomColors" />
        </VCardItem>
      </VCard>
    </VCol>
  </VRow>
</template>

<route lang="yaml">
meta:
  title: Transaction
  layout: default
  action: read
  subject: Auth
  active: 'transaction'
</route>
