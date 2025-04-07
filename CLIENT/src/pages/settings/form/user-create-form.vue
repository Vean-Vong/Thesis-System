<!-- eslint-disable import/no-unresolved -->
<script setup>
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'

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
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.post('/users-init').then(res => {
    form.options = res.data.data
  })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/users-create', form.data)
      .then(res => {
        if (res.status == 200) router.back()
      })
      .finally(() => {
        submitting.value = false
      })
  }
}
</script>

<template>
  <AppFormCreateTemplate
    cols="6"
    :title="$t('Create New User')"
    :submitting="submitting"
    @submit="onCreate"
  >
    <VForm
      ref="refForm"
      lazy-validation
    >
      <VRow>
        <VCol cols="12">
          <AppTextField
            id="username"
            v-model="form.data.username"
            :label="$t('User Name')"
            required="true"
            :rules="[v => !!v || $t('User Name') + $t('required')]"
          />
        </VCol>

        <VCol cols="12">
          <!-- prepend-inner-icon="tabler-code" -->
          <AppTextField
            id="email"
            v-model="form.data.email"
            :label="$t('Email')"
            required="true"
            :rules="[v => !!v || $t('Email') + $t('required')]"
          />
        </VCol>

        <VCol cols="12">
          <AppSelect
            id="role_ids"
            v-model="form.data.role_id"
            :items="form.options.roles"
            item-value="id"
            item-title="name"
            :label="$t('Role')"
            chip
            required="true"
            :rules="[v => !!v || $t('Role') + $t('required')]"
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: User Create
  layout: default
  subject: Auth
  active: 'user'
</route>
