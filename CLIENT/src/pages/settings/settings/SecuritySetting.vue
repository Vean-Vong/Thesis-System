<script setup>
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
const refForm = ref()
const isCurrentPasswordVisible = ref(false)
const isNewPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)

const form = ref({
  old_password: null,
  password: null,
  password_confirmation: null,
})

const passwordRequirements = [
  'Minimum 8 characters long - the more, the better',
  'At least one lowercase character',

  // "At least one number, symbol, or whitespace character",
]

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    api.post('/update-password', form.value).then(res => {
      if (res.status == 200) {
        form.value.old_password = null
        form.value.password = null
        form.value.password_confirmation = null
      }
    })
  }
}
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="12"
    >
      <VCard :title="$t('Change Password')">
        <VForm
          @submit.prevent="onUpdate"
          ref="refForm"
          lazy-validation
        >
          <VCardText class="pt-0">
            <!-- 👉 Current Password -->
            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 current password -->
                <AppTextField
                  v-model="form.old_password"
                  :type="isCurrentPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isCurrentPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  :rules="[v => !!v || $t('Password') + $t('required')]"
                  :label="$t('Password')"
                  @click:append-inner="isCurrentPasswordVisible = !isCurrentPasswordVisible"
                />
              </VCol>
            </VRow>

            <!-- 👉 New Password -->
            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 new password -->
                <AppTextField
                  v-model="form.password"
                  :type="isNewPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isNewPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  :rules="[v => !!v || $t('NewPassword') + $t('required')]"
                  :label="$t('NewPassword')"
                  @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 confirm password -->
                <AppTextField
                  v-model="form.password_confirmation"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  :rules="[v => !!v || $t('NewPasswordConfirm') + $t('required')]"
                  :label="$t('NewPasswordConfirm')"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                />
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 Password Requirements -->
          <VCardText>
            <h6 class="text-base font-weight-medium mb-3">{{ $t('Password Requirements') }}:</h6>

            <VList class="card-list">
              <VListItem
                v-for="item in passwordRequirements"
                :key="item"
                :title="$t(item)"
                class="text-medium-emphasis"
              >
                <template #prepend>
                  <VIcon
                    size="8"
                    icon="tabler-circle"
                    class="me-3"
                  />
                </template>
              </VListItem>
            </VList>
          </VCardText>

          <!-- 👉 Action Buttons -->
          <VCardText class="d-flex flex-wrap gap-4">
            <VBtn type="submit">{{ $t('Save changes') }}</VBtn>
          </VCardText>
        </VForm>
      </VCard>
    </VCol>
  </VRow>
</template>

<route lang="yaml">
meta:
  title: Security Setting
  layout: default
  subject: Auth
</route>
