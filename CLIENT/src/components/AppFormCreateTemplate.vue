<template>
  <VRow>
    <VCol
      cols="12"
      :md="cols"
    >
      <v-card>
        <v-card-title class="mt-2">
          <p class="text-primary px-6 font-weight-bold fontKSR text-lg-subtitle-1">
            {{ title }}
          </p>

          <v-divider class="mb-4"></v-divider>

          <div class="d-flex flex-wrap gap-1">
            <AppBackButton></AppBackButton>
            <AppSubmitButton
              :loading="submitting"
              :title="btnSubmit"
              @click.prevent="onSubmit"
              icon="tabler-table-shortcut"
            ></AppSubmitButton>
          </div>
        </v-card-title>

        <v-card-text class="mt-5">
          <slot />
        </v-card-text>
      </v-card>
    </VCol>
  </VRow>
</template>

<script setup>
import router from '@/router'

const props = defineProps({
  title: {
    type: String,
    default: 'Create New',
  },
  cols: {
    type: [Number, String],
    default: 6,
  },
  btnSubmit: {
    type: String,
  },
  submitting: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['submit'])

const onSubmit = () => {
  emit('submit')
}

const onGoBack = () => {
  router.go(-1)
}
</script>
