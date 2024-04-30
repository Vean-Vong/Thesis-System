<script setup>
import api from "@/plugins/utilites";
import router from "@/router";
const submitting = ref(false);
const refForm = ref();

const form = reactive({
  data: {
    id_card: null,
    name: null,
    sex: null,
    age: null,
    phone: null,
  },
  options: {
    sexs: [
      {
        id: 1,
        name: "Female",
      },
      {
        id: 2,
        name: "Male",
      },
    ],
  },
});

onMounted(async () => {
  const query = await router.currentRoute.value.query;
  api.post("/patients-show", { id: query.uuid }).then((res) => {
    form.data = res.data.data;
  });
});

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("/patients-update", form.data)
      .then((res) => {
        if (res.status == 200) router.back();
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};
</script>

<template>
  <AppFormUpdateTemplate
    cols="12"
    @submit="onUpdate"
    :title="$t('Update Patient')"
    :submitting="submitting"
  >
    <VForm ref="refForm" lazy-validation>
      <VRow>
        <VCol cols="12" md="12" offset-lg>
          <VRow>
            <VCol cols="12">
              <VRow>
                <VCol cols="12" md="6">
                  <AppTextField
                    id="id_card"
                    v-model="form.data.id_card"
                    :label="$t('id_card_no')"
                  />
                </VCol>
                <VCol cols="12" md="6">
                  <AppTextField
                    id="name"
                    v-model="form.data.name"
                    :label="$t('Name')"
                    required="true"
                    :rules="[(v) => !!v || $t('Name') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppSelectNew
                    id="sex"
                    v-model="form.data.sex"
                    :label="$t('Sex')"
                    :items="form.options.sexs"
                    item-title="name"
                    item-value="id"
                    required="true"
                    :rules="[(v) => !!v || $t('Sex') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="age"
                    v-model="form.data.age"
                    :label="$t('age')"
                    required="true"
                    :rules="[(v) => !!v || $t('age') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="6">
                  <AppTextField
                    id="phone"
                    v-model="form.data.phone"
                    :label="$t('Phone')"
                  />
                </VCol>
              </VRow>
            </VCol>
          </VRow>
        </VCol>
      </VRow>
    </VForm>
  </AppFormUpdateTemplate>
</template>

<route lang="yaml">
meta:
  title: Patient Update
  layout: default
  subject: Auth
  active: "patient"
</route>
