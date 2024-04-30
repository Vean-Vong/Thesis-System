<script setup>
import api from "@/plugins/utilites";
import router from "@/router";
const refForm = ref();
const submitting = ref(false);

const form = reactive({
  data: {
    id_card: null,
    name: null,
    sex: null,
    age: null,
    phone: null,
    address: null, //village_id
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
  additional: {
    address_name: null, //full text address
  },
});

const onCreate = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("/patients-create", form.data)
      .then((res) => {
        if (res.status == 200) router.go(-1);
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};
</script>

<template>
  <AppFormCreateTemplate
    cols="6"
    @submit="onCreate"
    :title="$t('Create New Patient')"
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
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Patient Create
  layout: default
  subject: Auth
  active: "transaction"
</route>
