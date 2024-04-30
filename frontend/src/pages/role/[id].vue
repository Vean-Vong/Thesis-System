<script setup>
import avatar1 from "@/assets/images/avatars/avatar-1.png";
import api from "@/utils/utilites";
import { reactive, ref, onMounted, nextTick } from "vue";
import { useRoute, useRouter } from "vue-router";

const router = useRouter();
const { params } = useRoute();
const submitting = ref(false);
const permissions = ref([]);

const form = reactive({
  id: params.id,
  name: null,
  permissions: [],
});

const refForm = ref();
const onSubmit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("roles-update", form)
      .then((res) => {
        router.push("/role");
      })
      .catch((res) => {
        console.log(res);
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};

const callPermission = () => {
  api
    .post("permissions-list")
    .then((res) => {
      permissions.value = res.data.data;
    })
    .catch((res) => {
      console.log(res);
    });
};

const callRole = () => {
  api
    .post("roles-show", {
      id: params.id,
    })
    .then((res) => {
      form.name = res.data.data.name;
      res.data.data.permissions.forEach((element) => {
        form.permissions.push(element.id);
      });
    });
};

onMounted(() => {
  callPermission();

  callRole();
});
</script>

<template>
  <VRow>
    <VCol cols="12" md="8" sm="12">
      <VCard title="កែប្រែតួនាទី និងសិទ្ធ">
        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm class="mt-6" ref="refForm" lazy-validation @submit.prevent="onSubmit()">
            <VRow>
              <VCol md="12" cols="12">
                <VTextField
                  v-model="form.name"
                  label="ឈ្មោះតួនាទី"
                  :rules="[(v) => !!v || 'ឈ្មោះតួនាទី តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <v-col cols="12">
                <fieldset class="fieldset-p">
                  <legend class="px-2 text-subtitle-1 font-weight-bold">ជ្រើសរើសសិទ្ធ</legend>
                  <v-card class="ma-5" v-for="parent in permissions" :key="parent.id">
                    <v-card-title>
                      <v-card-title>
                        {{ parent.name }}
                      </v-card-title>
                      <v-divider />
                      <v-card-text>
                        <v-row>
                          <v-col
                            class="py-0 my-0"
                            cols="12"
                            lg="4"
                            md="4"
                            sm="6"
                            xs="8"
                            style="height: 50px"
                            v-for="child in parent.childrens"
                            :key="child.id"
                          >
                            <v-checkbox
                              v-model="form.permissions"
                              :label="child.display_name"
                              :value="child.id"
                            ></v-checkbox>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card-title>
                  </v-card>
                </fieldset>
              </v-col>
              <!-- 👉 Form Actions -->
              <VCol cols="12" class="d-flex flex-wrap gap-4 justify-end">
                <VBtn type="submit" :loading="submitting" color="success"
                  ><VIcon>mdi-add</VIcon> រក្សាទុក</VBtn
                >
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
