<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/plugins/utilites'
import VueApexCharts from 'vue3-apexcharts'
import Id from '../academic-class/[id].vue';
const router = useRouter()
onMounted(() => {
  api.post('summary').then(response => {
    statistics.value.forEach(element => {
      element.stats = response.data[element.name]
    })
  })
})

const statistics = ref([
  {
    title: 'Class Total',
    stats: 0,
    icon: 'mdi-account-group-outline',
    color: 'primary',
    name: 'academic_classes',
    to: '/academic-class',
    i18nKey: 'total_academic_classes',
  },
  {
    title: 'Teacher Total',
    stats: 0,
    icon: 'mdi-account-tie',
    color: 'info',
    name: 'teachers',
    to: '/teacher',
    i18nKey: 'teachers_total',
  },
  {
    title: 'Student Total',
    stats: 0,
    icon: 'mdi-account-clock',
    color: 'success',
    name: 'students',
    to: '/student',
    i18nKey: 'total_students',
  },
  {
    title: 'User Total',
    stats: 0,
    icon: 'mdi-account-star',
    color: 'warning',
    name: 'users',
    to: '/user',
    i18nKey: 'total_users',
  },
])

const series = ref([{
  name: 'Inflation',
  data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2]
}])

const chartOptions = ref({
  chart: {
    height: 350,
    type: 'bar'
  },
  plotOptions: {
    bar: {
      borderRadius: 10,
      dataLabels: {
        position: 'top' // top, center, bottom
      }
    }
  },
  dataLabels: {
    enabled: true,
    formatter: function (val) {
      return val + "%"
    },
    offsetY: -20,
    style: {
      fontSize: '12px',
      colors: ["#304758"]
    }
  },
  xaxis: {
    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    position: 'top',
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5
        }
      }
    },
    tooltip: {
      enabled: true
    }
  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    labels: {
      show: false,
      formatter: function (val) {
        return val + "%"
      }
    }
  },
  title: {
    text: 'data of the student',
    floating: true,
    offsetY: 330,
    align: 'center',
    style: {
      color: '#444'
    }
  }
})

const go = to => {
  router.push(to)
}
</script>

<template>
  <VCard>
    <VCardItem>
      <VCardTitle class="text-xl text-primary">{{ $t('total_data_table') }}:</VCardTitle>
    </VCardItem>
    <VCardText>
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
            :class="stat-card"
            :color="item.color"
            class="d-flex align-center mb-4 py-5 rounded-2"
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
            <v-divider vertical class="text-white" :thickness="1"></v-divider>
            <div class="d-flex flex-column mx-4">
              <div class="">{{ $t(item.i18nKey) }}</div>
            </div>
            <span class="text-2xl font-weight-medium text-white ms-auto mx-5">{{ item.stats }}</span>
          </VCard>
        </VCol>
      </VRow>

      <div class="">
        <VueApexCharts type="bar" height="350" :options="chartOptions" :series="series"></VueApexCharts>
      </div>

    </VCardText>
  </VCard>
</template>

<script>
import VueApexCharts from 'vue3-apexcharts'
export default {
  components: {
    VueApexCharts
  }
}
</script>

<style scoped>
.link:hover {
  cursor: pointer;
}
.stat-card {
  background: linear-gradient(to bottom right, #42a5f5, #0d47a1); /* Gradient background */
}
</style>

<route lang="yaml">
  meta:
    title: Dashboard
    layout: default
    subject: Auth
    active: 'dashboard'
</route>
