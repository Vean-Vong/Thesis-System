<script setup>
import { useRoute } from 'vue-router'
import VueApexCharts from 'vue3-apexcharts'
import api from '@/plugins/utilites'

const route = useRoute()

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

const fetchData = async () => {
  loading.value = true
  await api.post(`academic-years-result`, {
      term_id: route.query.id
    })
    .then(res => {
      data.value = res.data.data
      console.log(data.value.englist.pass.total)
    })
    .finally(() => {
      loading.value = false
    })
}

fetchData()

// onMounted( async () => {
//   await fetchData()
// })

const chartOptions1 = {
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


const chartSeries1 = [
  {
    name: 'Actual',
    data: [
      {
        x: 'Passing ',
        y: data.value.englist.pass.total,
      },
      {
        x: 'Failing ',
        y: data.value.englist.fail.total,
      },
      {
        x: 'Drop-out',
        y: data.value.englist.drop.total,
      },
      {
        x: 'Award Certificate',
        y: data.value.englist.awd.total,
      },
      {
        x: 'Beginning  ',
        y: data.value.englist.beg.total,
      },
      {
        x: 'End of term II',
        y: data.value.englist.end.total,
      },
    ],
  },
]
const chartOptions2 = {
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

const chartSeries2 = [
  {
    name: 'Actual',
    data: [
      {
        x: 'Passing ',
        y: 91,
        goals: [
          {
            name: 'Expected',
            value: 91,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'Failing ',
        y: 0,
        goals: [
          {
            name: 'Expected',
            value: 0,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'Drop-out',
        y: 6,
        goals: [
          {
            name: 'Expected',
            value: 6,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'Award Certificate',
        y: 18,
        goals: [
          {
            name: 'Expected',
            value: 18,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'Beginning  ',
        y: 97,
        goals: [
          {
            name: 'Expected',
            value: 97,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'End of term II',
        y: 91,
        goals: [
          {
            name: 'Expected',
            value: 91,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
    ],
  },
]
</script>
<template>
  <div>
    <h1 style="text-align: center">Term Result II</h1>
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
            Beginning of term II
          </th>
          <th
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            End of term II
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
            Total: {{ data.computer.pass.total }}
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
            Total: {{ data.computer.pass.total }}
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
            Total: {{ data.computer.pass.total }}
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
          height="450"
          style="width: 100%"
          :options="chartOptions1"
          :series="chartSeries1"
        ></VueApexCharts>
        <h2 style="text-align: center">English Result Term II 2024</h2>
        <br />
        <ul style="list-style-type: square; display: flex; gap: 40px; justify-content: center; font-size: 18px">
          <li>Passing Students</li>
          <li>Failing Students</li>
          <li>Drop-out Students</li>
        </ul>
        <br />
        <ul style="list-style-type: square; display: flex; gap: 40px; justify-content: center; font-size: 18px">
          <li>Award Certificate</li>
          <li>Beginning of term II</li>
          <li>End of term II</li>
        </ul>
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
        <h2 style="text-align: center">Computer Result Term II 2024</h2>
        <br />
        <ul style="list-style-type: square; display: flex; gap: 40px; justify-content: center; font-size: 18px">
          <li>Passing Students</li>
          <li>Failing Students</li>
          <li>Drop-out Students</li>
        </ul>
        <br />
        <ul style="list-style-type: square; display: flex; gap: 40px; justify-content: center; font-size: 18px">
          <li>Award Certificate</li>
          <li>Beginning of term II</li>
          <li>End of term II</li>
        </ul>
        <br />
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
