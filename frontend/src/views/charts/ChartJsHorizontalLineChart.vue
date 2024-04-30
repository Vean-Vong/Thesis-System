<script setup>
import { useTheme } from "vuetify";
import { getLineChartConfig } from "@core/libs/chartjs/chartjsConfig";
import LineChart from "@core/libs/chartjs/components/LineChart";

const props = defineProps({
  colors: {
    type: null,
    required: true,
  },
});

const vuetifyTheme = useTheme();
const chartOptions = computed(() =>getLineChartConfig(vuetifyTheme.current.value));

const data = {
  labels: ["Jan", "Feb", "Mar ", "Apr", "May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
  datasets: [
    {
      label: "Market Data",
      backgroundColor: props.colors.warningShade,
      pointStyle: 'circle',
      pointRadius: 4, 
      borderColor: props.colors.warningShade,
      data: [40, 39, 10, 40, 39,45,12,66,100,103,123,233]
    },
    {
      backgroundColor: props.colors.horizontalBarInfo,
      label: "Personal Data",
      pointStyle: 'circle',
      pointRadius: 4,
      borderColor: props.colors.horizontalBarInfo,
      data: [20, 139, 103, 240, 209,12,45,333,129,237,23],
    },
  ],
};
</script>

<template>
  <LineChart :height="375" :chart-data="data" :chart-options="chartOptions" />
</template>
