<script setup>
import api from '@/plugins/utilites'
import router from '@/router'

const form = ref({})
const submitting = ref(false)
const refForm = ref()

onMounted(async () => {
  const query = await router.currentRoute.value.query

  api.post('/positions-show', { id: query.uuid }).then(res => {
    form.value = res.data.data
  })
})

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/positions-update', form.value)
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
  <AppFormUpdateTemplate
    @submit="onUpdate"
    :title="$t('Update Position')"
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
  </AppFormUpdateTemplate>
</template>

<route lang="yaml">
meta:
  title: Position Update
  layout: default
  subject: Auth
  active: 'position'
</route>
