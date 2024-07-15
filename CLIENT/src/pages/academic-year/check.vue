<script setup>
import { useRoute } from 'vue-router'
import VueApexCharts from 'vue3-apexcharts'

const route = useRoute()
console.log(route.query.id)

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
        y: 234,
        goals: [
          {
            name: 'Expected',
            value: 234,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'Failing ',
        y: 28,
        goals: [
          {
            name: 'Expected',
            value: 28,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'Drop-out',
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
        x: 'Award Certificate',
        y: 43,
        goals: [
          {
            name: 'Expected',
            value: 43,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'Beginning  ',
        y: 281,
        goals: [
          {
            name: 'Expected',
            value: 281,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
      },
      {
        x: 'End of term II',
        y: 262,
        goals: [
          {
            name: 'Expected',
            value: 262,
            strokeHeight: 5,
            strokeColor: '#775DD0',
          },
        ],
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
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F;</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
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
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
          <td
            colspan="2"
            style="border: 1px solid black; padding: 5px"
          >
            Total
          </td>
        </tr>
        <tr>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F;</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
          <td style="border: 1px solid black; padding: 5px">M:</td>
          <td style="border: 1px solid black; padding: 5px">F:</td>
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
    active: 'academic-class'
  </route>