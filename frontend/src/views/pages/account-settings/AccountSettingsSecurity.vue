<script setup>
import api from "@/utils/utilites";
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/store/module/auth.module";
const isCurrentPasswordVisible = ref(false);
const isNewPasswordVisible = ref(false);
const isConfirmPasswordVisible = ref(false);
const oldPassword = ref("");
const newPassword = ref("");
const confirmPassword = ref("");
const passwordRequirements = [
  "តម្រូវឱ្យមាន៨ខ្ទង់ឡើង ឬច្រើនជាងនេះកាន់តែប្រសើរ",
  "តម្រូវឱ្យមានអក្សរតូចមួយ និងអក្សរធំមួយ",
  "តម្រូវឱ្យមានលេខមួយខ្ទង់ ឬសញ្ញាមួយ",
];
const store = useAuthStore();

onMounted(() => {});
const submitHandler = () => {
  api
    .post(`update-password`, {
      old_password: oldPassword.value,
      password: newPassword.value,
      password_confirmation: confirmPassword.value,
    })
    .then((res) => {});
};
</script>

<template>
  <VRow>
    <!-- SECTION: Change Password -->
    <VCol cols="12">
      <VCard title="ប្ដូរពាក្យសម្ងាត់">
        <VForm @submit.prevent="submitHandler()">
          <VCardText>
            <!-- 👉 Current Password -->
            <VRow class="mb-3">
              <VCol cols="12" md="6">
                <!-- 👉 current password -->
                <VTextField
                  v-model="oldPassword"
                  :type="isCurrentPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isCurrentPasswordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'
                  "
                  label="ពាក្យសម្ងាត់ចាស់"
                  @click:append-inner="
                    isCurrentPasswordVisible = !isCurrentPasswordVisible
                  "
                />
              </VCol>

              <VCol cols="12" md="6">
                <!-- 👉 new password -->
                <VTextField
                  v-model="newPassword"
                  :type="isNewPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isNewPasswordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'
                  "
                  label="ពាក្យសម្ងាត់ថ្មី"
                  @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                />
              </VCol>

              <VCol cols="12" md="6">
                <VTextField
                  v-model="confirmPassword"
                  :type="isNewPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isNewPasswordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'
                  "
                  label="ផ្ទៀងផ្ទាត់ ពាក្យសម្ងាត់ថ្មី"
                  @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                />
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 Password Requirements -->
          <VCardText>
            <p class="text-base font-weight-medium mt-2">លក្ខណ្ឌបំពេញពាក្យសម្ងាត់:</p>

            <ul class="d-flex flex-column gap-y-3">
              <li v-for="item in passwordRequirements" :key="item" class="d-flex">
                <div>
                  <VIcon size="7" icon="mdi-circle" class="me-3" />
                </div>
                <span class="font-weight-medium">{{ item }}</span>
              </li>
            </ul>
          </VCardText>

          <!-- 👉 Action Buttons -->
          <VCardText class="d-flex flex-wrap gap-4">
            <VBtn type="submit" color="success">
              <VIcon>mdi-add</VIcon>
              រក្សាទុក
            </VBtn>

            <VBtn type="reset" color="secondary" variant="tonal"> កំណត់ឡើងវិញ </VBtn>
          </VCardText>
        </VForm>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>
</template>
