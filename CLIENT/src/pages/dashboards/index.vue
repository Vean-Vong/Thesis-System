<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/plugins/utilites'
import VueApexCharts from 'vue3-apexcharts'
import Id from '../academic-class/[id].vue';
import { useI18n } from 'vue-i18n'
const router = useRouter()
const { t } = useI18n()
const st_in_year = ref([])

onMounted(() => {
  api.post('summary').then(response => {
    
    statistics.value[0].stats = response.data.academic_classes
    statistics.value[1].stats = response.data.teachers
    statistics.value[2].stats = response.data.students
    statistics.value[3].stats = response.data.users
    statistics.value[4].stats = response.data.classes_this_term
    statistics.value[5].stats = response.data.new_students
    statistics.value[6].stats = response.data.new_students_m
    statistics.value[7].stats = response.data.new_students_f
    series.value[0].data = response.data.st_in_year
  })
})

const statistics = ref([
  {
    title: 'Class Total',
    stats: 0,
    icon: 'mdi-account-group-outline',
    color: '#3b28cc',
    name: 'academic_classes',
    to: '/academic-class',
    i18nKey: 'total_academic_classes',
  },
  {
    title: 'Teacher Total',
    stats: 0,
    icon: 'mdi-account-tie',
    color: 'primary',
    name: 'teachers',
    to: '/teacher',
    i18nKey: 'teachers_total',
  },
  {
    title: 'Student Total',
    stats: 0,
    icon: 'mdi-account-clock',
    color: '#2667ff',
    name: 'students',
    to: '/student',
    i18nKey: 'total_students',
  },
  {
    title: 'User Total',
    stats: 0,
    icon: 'mdi-account-star',
    color: '#3f8efc',
    name: 'users',
    to: 'settings/user-settings',
    i18nKey: 'total_users',
  },{
    title: 'Class in This Term',
    stats: 0,
    icon: 'mdi-account-group-outline',
    color: '#3b28cc',
    name: 'academic_classes',
    to: '/academic-class',
    i18nKey: 'Class in This Term',
  },
  {
    title: 'New Student this Term',
    stats: 0,
    icon: 'mdi-account-tie',
    color: 'primary',
    i18nKey: 'New Student this Term',
  },
  {
    title: 'New Student this Term Male',
    stats: 0,
    icon: 'mdi-account-clock',
    color: '#2667ff',
    i18nKey: 'New Student this Term Male',
  },
  {
    title: 'New Student this Term Female',
    stats: 0,
    icon: 'mdi-account-star',
    color: '#3f8efc',
    i18nKey: 'New Student this Term Female',
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
      return val
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
      show: true
    },
    labels: {
      show: false,
      formatter: function (val) {
        return val 
      }
    }
  },
  title: {
    text: t('Students Register in This Year'),
    floating: true,
    show: true,
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
    <!-- <VCardItem>
      <VCardTitle class="text-xl text-primary">{{ $t('total_data_table') }}:</VCardTitle>
    </VCardItem> -->
    <VCardText class="my-5">
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

      <div class="mt-12">
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
