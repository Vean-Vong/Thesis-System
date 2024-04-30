<template>
  <VRow>
    <VCol cols="12" :md="cols">
      <v-card>
        <v-card-title class="mt-2 py-5">
          <p class="text-primary px-6 font-weight-bold fontKSR text-lg-subtitle-1">
            {{ title }}
          </p>

          <div class="d-flex flex-wrap gap-2">
            <AppBackButton></AppBackButton>
            <slot name="btnSubmit" />
          </div>

          <v-divider class="mt-5"></v-divider>
        </v-card-title>

        <v-card-text class="mt-5">
          <slot />
        </v-card-text>
      </v-card>
    </VCol>
  </VRow>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    default: "Update",
  },
  cols: {
    type: [Number, String],
    default: 6,
  },
});
</script>
