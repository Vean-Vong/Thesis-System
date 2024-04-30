<script setup>
import Summary from "@/views/dashboards/Summary.vue";
</script>

<template>
  <VRow class="match-height">
    <!-- <VCol cols="12">
      <TotalEmployeeByType />
    </VCol>

    <VCol cols="12" md="8" sm="12" lg="8">
      <AnalyticsTotalProfitLineCharts />
    </VCol>
    <VCol cols="12" md="4" sm="12" lg="4">
      <TotalDataByTypes />
    </VCol> -->
    <VCol cols="12">
      <Summary />
    </VCol>
  </VRow>
</template>
