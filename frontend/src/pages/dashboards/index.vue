<script setup>
import ChartJsHorizontalLineChart from "@/views/charts/ChartJsHorizontalLineChart.vue";
import AppCardinfo from "@/components/AppCardInfo.vue";
import router from "@/router";

const chartJsCustomColors = {
  white: "#fff",
  yellow: "#ffe802",
  primary: "#836af9",
  areaChartBlue: "#2c9aff",
  barChartYellow: "#ffcf5c",
  polarChartGrey: "#4f5d70",
  polarChartInfo: "#299aff",
  lineChartYellow: "#d4e157",
  polarChartGreen: "#28dac6",
  lineChartPrimary: "#9e69fd",
  lineChartWarning: "#ff9800",
  horizontalBarInfo: "#26c6da",
  polarChartWarning: "#ff8131",
  scatterChartGreen: "#28c76f",
  warningShade: "#ffbd1f",
  areaChartBlueLight: "#84d0ff",
  areaChartGreyLight: "#edf1f4",
  scatterChartWarning: "#ff9f43",
};

const items = [
  {
    id: 1,
    icon: "mdi-plus",
    Name: "New Patient",
    backGroundColor: "bg-warning",
    To: "",
    type: "Add",
    link: '/transactions/create-patient',
  },
  {
    id: 2,
    icon: "mdi-human-male",
    Name: "Patient Today",
    backGroundColor: "bg-info",
    count: 0,
    To: "",
    type: "Total",
    link: '#',
  },
  {
    id: 3,
    icon: "mdi-human-male",
    Name: "Patient this year",
    backGroundColor: "bg-error",
    count: 0,
    To: "",
    type: "Total",
    link: '#',
  },
  {
    id: 4,
    icon: "mdi-human-male",
    Name: "Patient Staying",
    backGroundColor: "bg-warning",
    count: 0,
    To: "",
    type: "Total",
    link: '#',
  },
];

const viewList = (link) => {
  router.push(link);
};

</script>

<template>
  <VRow>
    <AppCardinfo v-for="ret in items" :key="ret.id" :item="ret"  @click="viewList(ret.link)"/>
  </VRow>
  <VRow>
    <VCol cols="12" md="12">
      <VCard class="pa-4">
        <VCardTitle class="text-center">Total Paition this year</VCardTitle>
        <VCardItem>
          <ChartJsHorizontalLineChart :colors="chartJsCustomColors" />
        </VCardItem>
      </VCard>
    </VCol>
  </VRow>
</template>

<route lang="yaml">
meta:
  title: Dashboard
  layout: default
  action: read
  subject: Auth
</route>
