<script setup>
import VueApexCharts from "vue3-apexcharts";
import { useTheme } from "vuetify";
import { hexToRgb } from "@layouts/utils";
import { onMounted, ref } from "vue";
import api from "../../../utils/utilites";

const vuetifyTheme = useTheme();
const currentTheme = computed(() => {
  return vuetifyTheme.current.value.colors;
});
const variableTheme = computed(() => {
  return vuetifyTheme.current.value.variables;
});

const year = ref("");

onMounted(() => {
  api.post("summary").then((response) => {
    series.value[0].data = response.data.series_births;
    series.value[1].data = response.data.series_marrieds;
    series.value[2].data = response.data.series_deaths;
    total.value = response.data.total;
    year.value = response.data.year;
  });
});
const series = ref([
  {
    name: "ចំនួនបញ្ចូលកំណើត",
    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  },
  {
    name: "ចំនួនបញ្ចូលអាពាពិពាហ៍",
    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  },
  {
    name: "ចំនួនបញ្ចូលមណៈភាព",
    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  },
]);

const total = ref(0);

const chartOptions = computed(() => {
  return {
    chart: {
      parentHeightOffset: 0,
      toolbar: { show: false },
      foreColor: "grey",
    },
    tooltip: { enabled: true },
    grid: {
      borderColor: `rgba(${hexToRgb(String(variableTheme.value["border-color"]))},${
        variableTheme.value["border-opacity"]
      })`,
      strokeDashArray: 6,
      xaxis: { lines: { show: true } },
      yaxis: { lines: { show: false } },
      padding: {
        top: -10,
        left: 15,
        right: 5,
        bottom: 5,
      },
    },
    stroke: {
      width: 3,
      lineCap: "butt",
      curve: "straight",
    },
    colors: [
      currentTheme.value.primary,
      currentTheme.value.info,
      currentTheme.value.warning,
    ],
    markers: {
      size: 6,
      offsetY: 4,
      offsetX: -2,
      strokeWidth: 3,
      colors: ["transparent"],
      strokeColors: "transparent",
      discrete: [
        {
          size: 5.5,
          seriesIndex: 0,
          strokeColor: currentTheme.value.primary,
          fillColor: currentTheme.value.surface,
          dataPointIndex: series.value[0].data.length - 1,
        },
      ],
      hover: { size: 7 },
    },
    xaxis: {
      labels: { show: true },
      axisTicks: { show: false },
      axisBorder: { show: false },
      categories: [
        "មករា",
        "កុម្ភៈ",
        "មីនា",
        "មេសា",
        "ឧសភា",
        "មិថុនា",
        "កក្កដា",
        "សីហា",
        "កញ្ញា",
        "តុលា",
        "វិច្ឆិកា",
        "ធ្នូ",
      ],
    },
    yaxis: { labels: { show: true } },
  };
});
</script>

<template>
  <VCard>
    <VCardText>
      <h6 class="text-h6 text-info">សរុប: {{ total }}</h6>
      <VueApexCharts type="line" :options="chartOptions" :series="series" :height="450" />

      <p class="text-center font-weight-semibold mb-0 text-info">
        ទិន្នន័យដែលបានបញ្ចូលសរុបក្នុងឆ្នាំ {{ year }}
      </p>
    </VCardText>
  </VCard>
</template>

<style>
.apexcharts-tooltip {
  background: #f4f5fa !important;
  color: #16b1ff !important;
}
</style>
