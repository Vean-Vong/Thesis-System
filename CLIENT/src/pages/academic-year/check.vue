<script setup>
import { useRoute } from 'vue-router'
import VueApexCharts from 'vue3-apexcharts'
import api from '@/plugins/utilites'
import { onMounted, ref, watch } from "vue";

const route = useRoute()

const term = ref({
  name: '',
})
const data = ref({
  englist: {
    pass: {M: 0, F: 0, total: 0},
    fail: {M: 0, F: 0, total: 0},
    drop: {M: 0, F: 0, total: 0},
    awd: {M: 0, F: 0, total: 0},
    beg: {M: 0, F: 0, total: 0},
    end: {M: 0, F: 0, total: 0},
  },
  computer: {
    pass: {M: 0, F: 0, total: 0},
    fail: {M: 0, F: 0, total: 0},
    drop: {M: 0, F: 0, total: 0},
    awd: {M: 0, F: 0, total: 0},
    beg: {M: 0, F: 0, total: 0},
    end: {M: 0, F: 0, total: 0},
  }
});
const loading = ref(false)


// onMounted( async () => {
//   await fetchData()
// })

const chartOptions1 = computed(() => {
  return {
    chart: {
    height: 350,
    type: 'bar',
  },
  plotOptions: {
    bar: {
      columnWidth: '60%',
    },
  },
  colors: ['#00E396'],
  dataLabels: {
    enabled: false,
  },
  // legend: {
  //   show: true,
  //   showForSingleSeries: true,
  //   customLegendItems: ['Actual', 'Expected'],
  //   markers: {
  //     fillColors: ['#00E396', '#775DD0'],
  //   },
  // },
}
  
})

const chartSeries1 = ref([
  {
    name: 'Actual',
    data: [
      {
        x: 'Passing ',
        y: 0,
      },
      {
        x: 'Failing ',
        y: 0,
      },
      {
        x: 'Drop-out',
        y: 0,
      },
      {
        x: 'Award Certificate',
        y: 0,
      },
      {
        x: 'Beginning  ',
        y: 0,
      },
      {
        x: 'End of term',
        y: 0,
      },
    ],
  },
])

const chartOptions2= computed(() => {
  return{
  chart: {
    height: 350,
    type: 'bar',
  },
  plotOptions: {
    bar: {
      columnWidth: '60%',
    },
  },
  colors: ['#766af7'],
  dataLabels: {
    enabled: false,
  },
  // legend: {
  //   show: true,
  //   showForSingleSeries: true,
  //   customLegendItems: ['Actual', 'Expected'],
  //   markers: {
  //     fillColors: ['#00E396', '#775DD0'],
  //   },
  // },
}
})

const chartSeries2 = ref([
  {
    name: 'Actual',
    data: [
      {
        x: 'Passing ',
        y: 0,
      },
      {
        x: 'Failing ',
        y: 0,
      },
      {
        x: 'Drop-out',
        y: 0,
      },
      {
        x: 'Award Certificate',
        y: 0,
      },
      {
        x: 'Beginning  ',
        y: 0,
        
      },
      {
        x: 'End of term',
        y: 0,
      
      },
    ],
  },
])

const fetchData = async () => {
  loading.value = true  
  await api.post(`academic-years-result`, {
    term_id: route.query.id,
  })
    .then(res => {
      term.value = res.data.term
      data.value = res.data.data

      chartSeries1.value[0].data[0].y = data.value.englist.pass.total
      chartSeries1.value[0].data[1].y = data.value.englist.fail.total
      chartSeries1.value[0].data[2].y = data.value.englist.drop.total
      chartSeries1.value[0].data[3].y = data.value.englist.awd.total
      chartSeries1.value[0].data[4].y = data.value.englist.beg.total
      chartSeries1.value[0].data[5].y = data.value.englist.end.total
      
      chartSeries2.value[0].data[0].y = data.value.computer.pass.total
      chartSeries2.value[0].data[1].y = data.value.computer.fail.total
      chartSeries2.value[0].data[2].y = data.value.computer.drop.total
      chartSeries2.value[0].data[3].y = data.value.computer.awd.total
      chartSeries2.value[0].data[4].y = data.value.computer.beg.total
      chartSeries2.value[0].data[5].y = data.value.computer.end.total
    })
    .finally(() => {
      loading.value = false
    })
}

fetchData()

</script>
<template>
  <div>
    <h1 style="text-align: center">Result {{ term.name }}</h1>
    <br />
    <table style="border: 1px solid black; padding: 5px; width: 100%; border-collapse: collapse; text-align: center">
      <thead>
        <tr style="font-family: 'Times New Roman', Times, serif; font-size: 17px">
          <th style="border: 1px solid black; padding: 5px">Course</th>
          <th
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Passing Students
          </th>
          <th
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Failing Students
          </th>
          <th
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Drop-out Students
          </th>
          <th
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Award Certificate
          </th>
          <th
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Beginning of Term
          </th>
          <th
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            End of Term
          </th>
        </tr>
      </thead>
      <tbody style="font-family: 'Times New Roman', Times, serif; font-size: 15px">
        <tr>
          <td
            rowspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            English
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total : {{ data.englist.pass.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total : {{ data.englist.fail.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total : {{ data.englist.drop.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total : {{ data.englist.awd.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total : {{ data.englist.beg.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total : {{ data.englist.end.total }}
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.englist.pass.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.englist.pass.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.englist.fail.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.englist.fail.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.englist.drop.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.englist.drop.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.englist.awd.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.englist.awd.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.englist.beg.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.englist.beg.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.englist.end.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.englist.end.F }}</td>
        </tr>
        <tr>
          <td
            rowspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Computer
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total: {{ data.computer.pass.total }} 
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total: {{ data.computer.fail.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total: {{ data.computer.drop.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total: {{ data.computer.awd.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total: {{ data.computer.beg.total }}
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total: {{ data.computer.end.total }}
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.computer.pass.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.computer.pass.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.computer.fail.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.computer.fail.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.computer.drop.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.computer.drop.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.computer.awd.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.computer.awd.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.computer.beg.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.computer.beg.F }}</td>
          <td style="border: 1px solid black; padding: 5px">M: {{ data.computer.end.M }}</td>
          <td style="border: 1px solid black; padding: 5px">F: {{ data.computer.end.F }}</td>
        </tr>
      </tbody>
    </table>
    <br />
    <p style="font-family: 'Times New Roman', Times, serif; font-size: 18px; text-align: left; font-weight: bold">
      Note: M= Male, F= Female
    </p>
    <div style="display: flex; justify-content: space-around; margin-top: 50px; gap: 20px">
      <div class="chart-card">
        <br />
        <h2 style="text-align: center">English Result Distribution</h2>
        <VueApexCharts
          type="bar"
          height="400"
          style="width: 100%"
          :options="chartOptions1"
          :series="chartSeries1"
        ></VueApexCharts>
        <h2 style="text-align: center">English Result  {{ term.name }}</h2>
        <br />
      </div>
      <div class="chart-card">
        <h2 style="text-align: center">Computer Result Distribution</h2>
        <VueApexCharts
          type="bar"
          height="450"
          style="width: 100%"
          :options="chartOptions2"
          :series="chartSeries2"
        ></VueApexCharts>
        <h2 style="text-align: center">Computer Result {{ term.name }}</h2>
        
      </div>
    </div>
    <br />
  </div>
</template>
<style scoped>
.chart-card {
  width: 40%;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}
</style>
<route lang="yaml">
  meta:
    title: Check Student
    layout: default
    subject: Auth
    active: 'academic-year'
  </route>
