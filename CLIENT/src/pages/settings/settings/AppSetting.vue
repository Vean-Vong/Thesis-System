<script setup>
// eslint-disable-next-line import/no-unresolved
import { useAuthStore } from '@/plugins/auth.module'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-unresolved
import avatar1 from '@images/avatars/avatar-1.png'
const authStore = useAuthStore()

const refInputEl = ref()
const accountDataLocal = ref(authStore._user)
const refForm = ref()
const submitting = ref(false)
const form = ref({
  app_settings: [],
})

onMounted(() => {
  if (!accountDataLocal.value.avatarImg) accountDataLocal.value.avatarImg = avatar1
  initForm()
})

const initForm = () => {
  api.post('app-settings-list').then(res => {
    if (res.status == 200) {
      form.value.app_settings = res.data.data
    }
  })
}

const changeAvatar = file => {
  const fileReader = new FileReader()
  const { files } = file.target
  if (files && files.length) {
    fileReader.readAsDataURL(files[0])
    console.log(files[0])
    fileReader.onload = () => {
      if (typeof fileReader.result === 'string') accountDataLocal.value.avatarImg = fileReader.result
    }
  }
}

// reset avatar image
const resetAvatar = () => {
  accountDataLocal.value.avatarImg = avatar1
}

const submitHandler = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('app-settings-save', form.value)
      .then(res => {
        if (res.status == 200) {
          // initForm();
        }
      })
      .finally(() => {
        submitting.value = false
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
      <VCard :title="$t('Profile Details')">
        <div v-for="ret in form.app_settings">
          <VCardText
            class="d-flex"
            v-if="ret.key == 'logo'"
          >
            <!-- 👉 Avatar -->
            <VAvatar
              rounded
              size="100"
              class="me-6"
              :image="ret.value"
            />

            <!-- 👉 Upload Photo -->
            <form class="d-flex flex-column justify-center gap-4">
              <div class="d-flex flex-wrap gap-2">
                <VBtn
                  color="primary"
                  @click="refInputEl?.click()"
                >
                  <VIcon
                    icon="tabler-cloud-upload"
                    class="d-sm-none"
                  />
                  <span class="d-none d-sm-block">{{ $t('Upload new photo') }}</span>
                </VBtn>

                <input
                  ref="refInputEl"
                  type="file"
                  name="file"
                  accept=".jpeg,.png,.jpg,GIF"
                  hidden
                  @input="changeAvatar"
                />

                <VBtn
                  type="reset"
                  color="secondary"
                  variant="tonal"
                  @click="resetAvatar"
                >
                  <span class="d-none d-sm-block">{{ $t('Reset') }}</span>
                  <VIcon
                    icon="tabler-refresh"
                    class="d-sm-none"
                  />
                </VBtn>
              </div>
            </form>
          </VCardText>
        </div>
        <VDivider />

        <VCardText class="pt-2">
          <VForm
            class="mt-6"
            ref="refForm"
            lazy-validation
            @submit.prevent="submitHandler"
          >
            <VRow>
              <template v-for="ret in form.app_settings">
                <VCol
                  md="6"
                  cols="12"
                  v-if="ret.key != 'logo'"
                >
                  <AppTextField
                    v-model="ret.value"
                    :label="$t(ret.label)"
                    :rules="[v => !!v || $t(ret.label) + $t('required')]"
                  />
                </VCol>
              </template>
            </VRow>

            <VCol
              cols="12"
              class="px-0 mt-2"
            >
              <VBtn
                :loading="submitting"
                type="submit"
                >{{ $t('Save changes') }}</VBtn
              >
            </VCol>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<route lang="yaml">
meta:
  title: Account Setting
  layout: default
  subject: Auth
</route>
