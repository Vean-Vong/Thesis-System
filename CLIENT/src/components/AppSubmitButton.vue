<template>
  <v-btn
    type="submit"
    variant="outlined"
    color="primary"
    class="mr-2"
    style="width: auto"
  >
    <v-icon v-if="showIcon" left class="mr-2">{{
      icon ? icon : "tabler-table-plus"
    }}</v-icon>
    {{ $t(title) }}
  </v-btn>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    default: "Save changes",
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
