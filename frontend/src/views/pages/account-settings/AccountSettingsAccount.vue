<script setup>
import avatar1 from "@/assets/images/avatars/avatar-1.png";
import { onMounted, ref, reactive, nextTick } from "vue";
import api from "@/utils/utilites";
import { useRouter } from "vue-router";
import axios from "axios";
import constant from "@/constants";
import { useAuthStore } from "@/store/module/auth.module";
const perPage = ref(99999);
const orgs = ref([]);
const roles = ref([]);
const submitting = ref(false);
const additional_image = ref(avatar1);
const localImage = ref("");
const refForm = ref(null);
const router = useRouter();
const store = useAuthStore();

const form = {
  id: null,
  email: null,
  photo_path: null,
  city: null,
  address: null,
  region: null,
  phone: null,
  contact_id: null,
};
const refInputEl = ref();
const formDataLocal = ref(structuredClone(form));

const resetForm = () => {
  formDataLocal.value = structuredClone(form);
};
const changeAvatar = (file) => {
  const fileReader = new FileReader();
  const { files } = file.target;
  if (files && files.length) {
    localImage.value = null;
    fileReader.readAsDataURL(files[0]);
    formDataLocal.value.photo_path = files[0];
    fileReader.onload = () => {
      if (typeof fileReader.result === "string")
        additional_image.value = fileReader.result;
    };
  }
};

// reset avatar image
const resetAvatar = () => {
  additional_image.value = null;

  // formDataLocal.value.photo_path = null
  form.photo_path = null;
};

const submitHandler = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    let formData = new FormData();

    formData.append("email", formDataLocal.value.email);
    formData.append("id", formDataLocal.value.id);
    if (formDataLocal.value.photo_path) {
      formData.append("photo_path", formDataLocal.value.photo_path);
    }
    submitting.value = true;
    api
      .post("update-profile", formData)
      .then((res) => {
        console.log(res.data);
        getProfileAcc();
      })
      .catch((res) => {
        console.log(res);
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};

const getProfileAcc = () => {
  api.post("verify-auth").then((res) => {
    formDataLocal.value.email = res.data.user?.email;
    formDataLocal.value.id = res.data.user?.id;

    localImage.value = res.data.user?.photo_path;

    nextTick(() => {
      store.$patch((state) => {
        state._user = res.data.user;
      });
    });

    // additional_image.value = res.data?.user?.photo_path

    // console.log(res.data.user?.photo_path)
  });
};

onMounted(() => {
  getProfileAcc();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="ផ្លាស់ប្តូរ គណនី">
        <VCardText class="d-flex">
          <!-- 👉 Avatar -->

          <VAvatar
            v-if="localImage"
            rounded="lg"
            size="100"
            class="me-6"
            :image="constant.storagePath + localImage"
          />

          <VAvatar
            v-else-if="!localImage"
            rounded="lg"
            size="100"
            class="me-6"
            :image="additional_image"
          />

          <!-- 👉 Upload Photo -->
          <div class="d-flex flex-column justify-center gap-5">
            <div class="d-flex flex-wrap gap-2">
              <VBtn color="success" @click="refInputEl?.click()">
                <VIcon icon="mdi-cloud-upload-outline" class="d-sm-none" />
                <span class="d-none d-sm-block">ដាក់រូបភាពថ្មី</span>
              </VBtn>

              <input
                ref="refInputEl"
                type="file"
                name="file"
                accept=".jpeg,.png,.jpg,GIF"
                hidden
                @input="changeAvatar"
              />

              <VBtn type="reset" color="error" variant="tonal" @click="resetAvatar">
                <span class="d-none d-sm-block">កំណត់ឡើងវិញ</span>
                <VIcon icon="mdi-refresh" class="d-sm-none" />
              </VBtn>
            </div>

            <p class="text-body-1 mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
          </div>
        </VCardText>

        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm
            ref="refForm"
            class="mt-6"
            lazy-validation
            @submit.prevent="submitHandler"
          >
            <VRow>             
              <VCol md="6" cols="12">
                <VTextField
                  v-model="formDataLocal.email"
                  label="អុីម៉ែល"
                  :rules="[(v) => !!v || 'អុីម៉ែល តម្រូវឱ្យបំពេញ']"
                />
              </VCol>            
              <!-- 👉 Form Actions -->
              <VCol cols="12" class="d-flex flex-wrap gap-4">
                <VBtn type="submit" color="success">
                  <VIcon>mdi-add</VIcon>
                  រក្សាទុក
                </VBtn>

                <VBtn
                  color="secondary"
                  variant="tonal"
                  type="reset"
                  @click.prevent="resetForm"
                >
                  Reset
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
