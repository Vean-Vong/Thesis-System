import router from '@/router';
<template>
  <v-btn
    @click.prevent="goBack"
    variant="outlined"
    color="primary"
    class="mr-2"
    style="width: auto"
  >
    <v-icon
      left
      class="mr-2"
      >tabler-arrow-left</v-icon
    >
    {{ $t(title) }}
  </v-btn>
</template>

<script setup>
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'

const props = defineProps({
  title: {
    type: String,
    default: 'Back',
  },
})

const goBack = () => {
  router.go(-1)
}
</script>
