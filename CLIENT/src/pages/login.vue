<script setup>
import { VForm } from "vuetify/components/VForm";
import AuthProvider from "@/views/pages/authentication/AuthProvider.vue";
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant";
import authV2LoginIllustrationBorderedDark from "@images/pages/auth-v2-login-illustration-bordered-dark.png";
import authV2LoginIllustrationBorderedLight from "@images/pages/auth-v2-login-illustration-bordered-light.png";
import authV2LoginIllustrationDark from "@images/pages/auth-v2-login-illustration-dark.png";
import authV2LoginIllustrationLight from "@images/pages/auth-v2-login-illustration-light.png";
import authV2MaskDark from "@images/pages/misc-mask-dark.png";
import authV2MaskLight from "@images/pages/misc-mask-light.png";
import { VNodeRenderer } from "@layouts/components/VNodeRenderer";
import { themeConfig } from "@themeConfig";
import { useAuthStore } from "@/plugins/auth.module";
import app_login_account_logo from "@images/app_account_login_logo.png";

const authThemeImg = useGenerateImageVariant(
  authV2LoginIllustrationLight,
  authV2LoginIllustrationDark,
  authV2LoginIllustrationBorderedLight,
  authV2LoginIllustrationBorderedDark,
  true
);

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark);

const isPasswordVisible = ref(false);
const refVForm = ref();

const form = ref({
  email: "admin",
  password: "Pwd@12345",

});

const onSubmit = () => {
  useAuthStore().login(form.value);
};

const logo = ref(
  h("img", {
    src: app_login_account_logo,
    style: "line-height:0; color: rgb(var(--v-global-theme-primary)); width: 40px",
  })
);
</script>

<template>
  <title>Login</title>
  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol lg="8" class="d-none d-lg-flex">
      <div class="position-relative bg-background rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100 flex-column">
          <VNodeRenderer
            style="width: 800px"
            :nodes="themeConfig.app.app_login_image"
            class="mb-6"
          />
        </div>
      </div>
    </VCol>

    <VCol cols="12" lg="4" class="auth-card-v2 d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4 text-center">
        <VCardText>
          <VNodeRenderer style="width: 150px" :nodes="logo" class="mb-6" />

        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onSubmit">
            <VRow>
              <!-- email -->
              <VCol cols="12" class="text-left">
                <AppTextField
                  v-model="form.email"
                  label="Username or Email"
                  type="email"
                  autofocus
                  append-inner-icon="mdi-person"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12" class="text-left">
                <AppTextField
                  v-model="form.password"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div
                  class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4"
                >
                
                </div>

                <VBtn block type="submit"> Login </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  title: Login
  layout: blank
</route>
