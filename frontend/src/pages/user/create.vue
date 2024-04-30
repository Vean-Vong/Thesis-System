<script setup>
import avatar1 from "@/assets/images/avatars/avatar-1.png";
import { onMounted, ref, reactive, computed } from "vue";
import api from "@/utils/utilites";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/store/module/auth.module";
import User from "../../class/User";

const store = useAuthStore();

const user = computed(() => {
  const data = {
    user: store?._user,
  };
  return new User(data);
});

const perPage = ref(99999);
const schools = ref([]);
const roles = ref([]);
const submitting = ref(false);
const additional_image = ref(avatar1);
const refForm = ref(null);
const router = useRouter();

const form = {
  school_id: null,
  username: null,
  email: null,
  photo_path: null,
  role_id: null,
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
  additional_image.value = avatar1;
  formDataLocal.value.photo_path = null;
};

const callOrgRole = () => {
  api.post("users-call-org-role").then((res) => {
    roles.value = res.data.data.roles;
    schools.value = res.data.data.schools;
  });
};

const submitHandler = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    let formData = new FormData();

    if (formDataLocal.value.school_id) {
      formData.append("school_id", formDataLocal.value.school_id);
    }
    formData.append("name", formDataLocal.value.name);
    formData.append("username", formDataLocal.value.username);
    formData.append("email", formDataLocal.value.email);
    formData.append("role_id", formDataLocal.value.role_id);
    if (formDataLocal.value.photo_path) {
      formData.append("photo_path", formDataLocal.value.photo_path);
    }
    api
      .post("users-create", formData)
      .then((res) => {
        router.push("/user");
      })
      .catch((res) => {
        console.log(res);
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};

onMounted(() => {
  callOrgRole();
});
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="បង្កើតអ្នកប្រើប្រាស់">
        <VCardText class="d-flex">
          <!-- 👉 Avatar -->
          <VAvatar rounded="lg" size="100" class="me-6" :image="additional_image" />

          <!-- 👉 Upload Photo -->
          <div class="d-flex flex-column justify-center gap-5">
            <div class="d-flex flex-wrap gap-2">
              <VBtn color="success" @click="refInputEl?.click()">
                <VIcon icon="mdi-cloud-upload-outline" class="d-sm-none" />
                <span class="d-none d-sm-block">Upload new photo</span>
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
                <span class="d-none d-sm-block">Reset</span>
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
            class="mt-6"
            ref="refForm"
            lazy-validation
            @submit.prevent="submitHandler()"
          >
            <VRow>
              <VCol md="6" cols="12">
                <VTextField
                  v-model="formDataLocal.username"
                  label="ឈ្មោះអ្នកប្រើប្រាស់"
                  :rules="[(v) => !!v || 'ឈ្មោះអ្នកប្រើប្រាស់ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>           
              <VCol md="6" cols="12">
                <VTextField
                  v-model="formDataLocal.email"
                  label="អុីម៉ែល"
                  :rules="[(v) => !!v || 'អុីម៉ែល តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="6" cols="12">
                <v-select
                  v-model="formDataLocal.role_id"
                  :items="roles"
                  item-title="name"
                  item-value="id"
                  label="តួនាទី"
                ></v-select>
              </VCol>

              <VCol md="6" cols="12" v-if="user.isAdmin()">
                <v-autocomplete
                  v-model="formDataLocal.school_id"
                  :items="schools"
                  item-title="khmer_name"
                  item-value="id"
                  :menu-props="{ maxHeight: 200 }"
                  label="សាលារៀន"
                ></v-autocomplete>
              </VCol>
              <!-- 👉 Form Actions -->
              <VCol cols="12" class="d-flex flex-wrap gap-4">
                <VBtn type="submit" :loading="submitting" color="success">
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
