<script setup>
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'

const form = ref({
  khmer_name: '',
  latin_name: '',
  sort: 1,
})

const refForm = ref()
const submitting = ref(false)

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/positions-create', form.value)
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
    @submit="onCreate"
    :title="$t('Create New Position')"
    :submitting="submitting"
  >
    <VForm
      ref="refForm"
      lazy-validation
    >
      <VRow>
        <VCol cols="12">
          <AppTextField
            id="khmer_name"
            v-model="form.khmer_name"
            :label="$t('Khmer Name')"
            required="true"
            :rules="[v => !!v || $t('Khmer Name') + $t('required')]"
          />
        </VCol>

        <VCol cols="12">
          <AppTextField
            id="latin_name"
            v-model="form.latin_name"
            :label="$t('Latin Name')"
          />
        </VCol>
        <VCol cols="12">
          <AppTextField
            id="sort"
            v-model="form.sort"
            type="number"
            :label="$t('Sort Number')"
            required="true"
            :rules="[v => !!v || $t('Sort Number') + $t('required')]"
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Position Create
  layout: default
  subject: Auth
  active: 'position'
</route>
