<script setup>
import { useTheme } from "vuetify";
import { getPolarChartConfig } from "@core/libs/chartjs/chartjsConfig";
import PolarAreaChart from "@core/libs/chartjs/components/PolarAreaChart";

const props = defineProps({
  colors: {
    type: null,
    required: true,
  },
});

const vuetifyTheme = useTheme();
const chartOptions = computed(() =>
getPolarChartConfig(vuetifyTheme.current.value)
);

const data = {
  labels: ["Total", "Staying"],
  datasets: [
    {
      maxBarThickness: 15,
      label: "Total",
      // backgroundColor: props.colors.warningShade,
      backgroundColor:[
        'rgba(255, 99, 132,0.5)',
        'rgba(255, 159, 64,0.5)',
        'rgba(255, 205, 86,0.5)',
        'rgba(75, 192, 192,0.5)',
        'rgba(153, 102, 255,0.5)',
        'rgba(201, 203, 207,0.5)',
        'rgba(54, 162, 235,0.5)'
      ],
      data: [200],
    },
    {
      maxBarThickness: 15,
      backgroundColor: [
        'rgba(255, 99, 132,0.5)',
        'rgba(255, 159, 64,0.5)',
        'rgba(255, 205, 86,0.5)',
        'rgba(75, 192, 192,0.5)',
        'rgba(153, 102, 255,0.5)',
        'rgba(201, 203, 207,0.5)',
        'rgba(54, 162, 235,0.5)'
      ],
      label: "Staying",
      data: [100],
    },
  ],
};
</script>

<template>
  <PolarAreaChart :height="375" :chart-data="data" :chart-options="chartOptions" />
</template>
