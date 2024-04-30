<script setup>
import { useTheme } from "vuetify";
import AuthProvider from "@/views/pages/authentication/AuthProvider.vue";
import authV1MaskDark from "@/assets/images/pages/auth-v1-mask-dark.png";
import authV1MaskLight from "@/assets/images/pages/auth-v1-mask-light.png";
import authV1Tree2 from "@/assets/images/pages/auth-v1-tree-2.png";
import authV1Tree from "@/assets/images/pages/auth-v1-tree.png";
import { useAuthStore } from "@/store/module/auth.module";
import { onMounted } from "vue";

const store = useAuthStore();

const form = ref({
  email: "",
  password: "",
  remember: false,
});
const vuetifyTheme = useTheme();
const authThemeMask = computed(() => {
  return vuetifyTheme.global.name.value === "light" ? authV1MaskLight : authV1MaskDark;
});
const isPasswordVisible = ref(false);

const onLogin = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    store.login(form.value);
  }
};
const refForm = ref();
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard class="auth-card pa-4 pt-7" style="width: 500px">
      <VCardItem class="justify-center">
        <VCardTitle
          class="font-weight-semibold text-xl text-uppercase muol-light text-success"
        >
          ប្រព័ន្ធគ្រប់គ្រងសាលារៀន
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <p class="mb-0">សូមចូលដោយប្រើប្រាស់គណនីរបស់អ្នក</p>
      </VCardText>

      <VCardText>
        <VForm ref="refForm" lazy-validation @submit.prevent="onLogin">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                label="ឈ្មោះគណនី ឬអ៊ីម៉ែល"
                :rules="[(v) => !!v || 'ឈ្មោះគណនី ឬអ៊ីម៉ែល តម្រូវឱ្យបំពេញ']"
                type="email"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                label="ពាក្យសម្ងាត់"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="
                  isPasswordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'
                "
                :rules="[(v) => !!v || 'ពាក្យសម្ងាត់ តម្រូវឱ្យបំពេញ']"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <!-- remember me checkbox -->
              <div
                class="d-flex align-center justify-space-between flex-wrap mt-1 mb-4 p-5"
              >
                <!-- <VCheckbox
                  v-model="form.remember"
                  label="Remember me"
                /> -->

                <!-- <a
                  class="ms-2 mb-1"
                  href="javascript:void(0)"
                >
                  Forgot Password?
                </a>-->
              </div>

              <!-- login button -->
              <VBtn block type="submit" color="success"> ចូលប្រើ </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>

    <VImg
      class="auth-footer-start-tree d-none d-md-block"
      :src="authV1Tree"
      :width="250"
    />

    <VImg
      :src="authV1Tree2"
      class="auth-footer-end-tree d-none d-md-block"
      :width="350"
    />

    <!-- bg img -->
    <VImg class="auth-footer-mask d-none d-md-block" :src="authThemeMask" />
  </div>
</template>

<style lang="scss">
@use "@core/scss/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
</route>
