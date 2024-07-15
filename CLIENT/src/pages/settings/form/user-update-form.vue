<script setup>
import api from "@/plugins/utilites";
import router from "@/router";

const form = reactive({
  data: {
    username: null,
    email: null,
    employee_id: null,
    role_id: null,
  },
  options: {
    roles: [],
    employees: [],
  },
});

const submitting = ref(false);
const refForm = ref();

onMounted(async () => {
  await initForm();
});

const initForm = async () => {
  const query = router.currentRoute.value.query;
  await api.post("/users-show", { id: query.uuid }).then((res) => {
    form.data = res.data.data;
  });

  await api.post("users-init").then((res) => {
    form.options = res.data.data;
  });

  form.data.role_id = form.data.roles[0].id
};

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("/users-update", form.data)
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
    @submit="onUpdate"
    :title="$t('Update User')"
    :submitting="submitting"
  >
    <VForm ref="refForm" lazy-validation>
      <VRow>
        <VCol cols="12">
          <AppTextField
            id="username"
            v-model="form.data.username"
            :label="$t('User Name')"
            required="true"
            :rules="[(v) => !!v || $t('User Name') + $t('required')]"
          />
        </VCol>

        <VCol cols="12">
          <!-- prepend-inner-icon="tabler-code" -->
          <AppTextField
            id="email"
            v-model="form.data.email"
            :label="$t('Email')"
            required="true"
            :rules="[(v) => !!v || $t('Email') + $t('required')]"
          />
        </VCol>
<!-- 
        <VCol cols="12">
          <AppAutocomplete
            id="staff_id"
            v-model="form.data.employee_id"
            :items="form.options.employees"
            item-value="id"
            item-title="name"
            :label="$t('Select Employee')"
            chip
          />
        </VCol> -->

        <VCol cols="12">
          <AppSelect
            id="role_id"
            v-model="form.data.role_id"
            :items="form.options.roles"
            item-value="id"
            item-title="name"
            :label="$t('Role')"
            chip
            required="true"
            :rules="[(v) => !!v || $t('Role') + $t('required')]"
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormUpdateTemplate>
</template>

<route lang="yaml">
meta:
  title: User Update
  layout: default
  subject: Auth
  active: "user"
</route>
