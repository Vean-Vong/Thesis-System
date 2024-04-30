<script setup>
import { useTheme } from "vuetify";
import { getPieChartJs } from "@core/libs/chartjs/chartjsConfig";
import PieChart from "@core/libs/chartjs/components/PieChart";

const props = defineProps({
  colors: {
    type: null,
    required: true,
  },
});

const vuetifyTheme = useTheme();
const chartOptions = computed(() =>
getPieChartJs(vuetifyTheme.current.value)
);

const data = {
  labels: ["Total", "Staying"],
  datasets: [
    {
      maxBarThickness: 15,
      backgroundColor: [
      'rgba(255, 99, 132,0.5)',
        'rgba(75, 192, 192,0.5)',
        'rgba(153, 102, 255,0.5)',
        'rgba(201, 203, 207,0.5)',
        'rgba(54, 162, 235,0.5)'
      ],
      borderColor: "transparent",
      data: [100,345],
    },
  ],
};
</script>

<template>
  <PieChart :height="375" :chart-data="data" :chart-options="chartOptions" />
</template>
