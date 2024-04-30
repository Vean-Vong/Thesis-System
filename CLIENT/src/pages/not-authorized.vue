<script setup>
import page401 from "@images/pages/401.png";
import miscMaskDark from "@images/pages/misc-mask-dark.png";
import miscMaskLight from "@images/pages/misc-mask-light.png";
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant";
import router from "@/router";

const authThemeMask = useGenerateImageVariant(miscMaskLight, miscMaskDark);

const goBack = () => {
  router.go(-2);
};
</script>

<template>
  <div class="misc-wrapper">
    <div class="misc-center-content text-center mb-12">
      <!-- 👉 Title and subtitle -->
      <h4 class="text-h4 font-weight-medium mb-3">
        You are not authorized! 🔐
      </h4>
      <p>You don't have permission to access this page. Go Home!</p>
      <VBtn @click.prevent="goBack()"> Go Back </VBtn>
    </div>

    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg :src="page401" alt="Coming Soon" :max-width="170" class="mx-auto" />
    </div>

    <VImg :src="authThemeMask" class="misc-footer-img d-none d-md-block" />
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/misc.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
</route>
