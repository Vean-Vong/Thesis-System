<template>
  <v-btn
    type="submit"
    variant="outlined"
    color="primary"
    class="mr-2"
    style="width: auto"
  >
    <v-icon v-if="showIcon" left class="mr-2">{{ icon ? icon : "mdi-search" }}</v-icon>
    {{ title }}
  </v-btn>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    default: "ស្វែងរក",
  },
  icon: {
    type: String,
  },
  showIcon: {
    type: [Boolean, Number],
    default: true,
  },
});
</script>
