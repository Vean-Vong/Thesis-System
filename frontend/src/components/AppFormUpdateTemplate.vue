<template>
  <VRow>
    <VCol cols="12" :md="cols">
      <v-card>
        <v-card-title class="mt-2">
          <p class="text-primary px-6 font-weight-bold fontKSR text-lg-subtitle-1">
            {{ title }}
          </p>

          <div class="d-flex flex-wrap gap-2">
            <AppBackButton></AppBackButton>

            <AppSubmitButton
              :loading="submitting"
              :title="btnSubmit"
              @click.prevent="onSubmit"
            ></AppSubmitButton>
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
import router from "@/router";

const props = defineProps({
  title: {
    type: String,
    default: "Update",
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
});

const emit = defineEmits(["submit"]);

const onSubmit = () => {
  emit("submit");
};

const onGoBack = () => {
  router.go(-1);
};
</script>
