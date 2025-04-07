<script setup>
// eslint-disable-next-line import/extensions, import/no-unresolved
import { useLoadingStore } from '@/plugins/loadingStore'

const isDialogVisible = ref(useLoadingStore().loading)
</script>

loadingStore
<template>
  <!-- Dialog -->
  <VDialog
    v-model="isDialogVisible"
    width="300"
  >
    <VProgressLinear
      class="text-primary"
      indeterminate
    />
  </VDialog>
</template>
