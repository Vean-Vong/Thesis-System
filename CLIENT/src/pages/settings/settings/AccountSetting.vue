<script setup>
import { useAuthStore } from "@/plugins/auth.module";
import api from "@/plugins/utilites";
import avatar1 from "@images/avatars/avatar-1.png";
const authStore = useAuthStore();

const refInputEl = ref();
const accountDataLocal = ref(authStore._user);
const refForm = ref();
const submitting = ref(false);
onMounted(() => {
  if (!accountDataLocal.value.avatarImg) accountDataLocal.value.avatarImg = avatar1;
});

const changeAvatar = (file) => {
  const fileReader = new FileReader();
  const { files } = file.target;
  if (files && files.length) {
    fileReader.readAsDataURL(files[0]);
    console.log(files[0]);
    fileReader.onload = () => {
      if (typeof fileReader.result === "string")
        accountDataLocal.value.avatarImg = fileReader.result;
    };
  }
};

// reset avatar image
const resetAvatar = () => {
  accountDataLocal.value.avatarImg = avatar1;
};

const submitHandler = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    let formData = new FormData();
    formData.append("email", accountDataLocal.value.email);
    formData.append("id", accountDataLocal.value.id);
    if (accountDataLocal.value.avatarImg !== avatar1) {
      formData.append("photo_path", accountDataLocal.value.avatarImg);
    }
    submitting.value = true;
    api
      .post("update-profile", formData)
      .then((res) => {
        getProfileAcc();
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};

const getProfileAcc = () => {
  api.post("verify-auth").then((res) => {
    accountDataLocal.value.email = res.data.user?.email;
    accountDataLocal.value.username = res.data.user?.username;
    accountDataLocal.value.id = res.data.user?.id;

    if (res.data.user?.photo_path) {
      accountDataLocal.value.avatarImg = res.data.user?.photo_path;
    }

    nextTick(() => {
      authStore.$patch((state) => {
        state._user = res.data.user;
      });
    });
  });
};
</script>

<template>
  <VRow>
    <VCol cols="12" md="12">
      <VCard :title="$t('Profile Details')">
        <VCardText class="d-flex">
          <!-- 👉 Avatar -->
          <VAvatar rounded size="100" class="me-6" :image="accountDataLocal.avatarImg" />

          <!-- 👉 Upload Photo -->
          <form class="d-flex flex-column justify-center gap-4">
            <div class="d-flex flex-wrap gap-2">
              <VBtn color="primary" @click="refInputEl?.click()">
                <VIcon icon="tabler-cloud-upload" class="d-sm-none" />
                <span class="d-none d-sm-block">{{ $t("Upload new photo") }}</span>
              </VBtn>

              <input
                ref="refInputEl"
                type="file"
                name="file"
                accept=".jpeg,.png,.jpg,GIF"
                hidden
                @input="changeAvatar"
              />

              <VBtn type="reset" color="secondary" variant="tonal" @click="resetAvatar">
                <span class="d-none d-sm-block">{{ $t("Reset") }}</span>
                <VIcon icon="tabler-refresh" class="d-sm-none" />
              </VBtn>
            </div>

            <!-- <p class="text-body-1 mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p> -->
          </form>
        </VCardText>

        <VDivider />

        <VCardText class="pt-2">
          <!-- 👉 Form -->
          <!-- <fieldset class="px-4 rounded pb-4">
            <legend>User Information</legend> -->
          <VForm
            class="mt-6"
            ref="refForm"
            lazy-validation
            @submit.prevent="submitHandler"
          >
            <VRow>
              <VCol md="6" cols="12">
                <AppTextField
                  v-model="accountDataLocal.username"
                  disabled
                  :label="$t('User Name')"
                />
              </VCol>
              <VCol md="6" cols="12">
                <AppTextField
                  v-model="accountDataLocal.email"
                  :label="$t('Email')"
                  :rules="[(v) => !!v || $t('Email') + $t('required')]"
                />
              </VCol>
              <!-- <VCol md="6" cols="12">
                  <AppAutocomplete
                    label="Organization"
                    :items="organizations"
                    item-value="id"
                    item-title="name"
                    v-model="accountDataLocal.organization.id"
                  />
                </VCol> -->
              <!-- <VCol md="6" cols="12">
                  <AppAutocomplete
                    label="Role"
                    multiple
                    :items="roles"
                    v-model="accountDataLocal.role_name"
                  />
                </VCol> -->
            </VRow>

            <VCol cols="12" class="px-0 mt-2">
              <VBtn :loading="submitting" type="submit">{{ $t("Save changes") }}</VBtn>
            </VCol>
          </VForm>
          <!-- </fieldset> -->
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
