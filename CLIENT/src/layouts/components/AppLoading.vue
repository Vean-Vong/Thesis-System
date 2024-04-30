<script setup>
import { useLoadingStore } from "@/plugins/loadingStore";

const isDialogVisible = ref(useLoadingStore().loading);
</script>
loadingStore
<template>
  <!-- Dialog -->
  <VDialog v-model="isDialogVisible" width="300">
    <!-- <VCard color="primary" width="300">
      <VCardText> -->
    <VProgressLinear class="text-primary" indeterminate />
    <!-- </VCardText>
    </VCard> -->
  </VDialog>
</template>
