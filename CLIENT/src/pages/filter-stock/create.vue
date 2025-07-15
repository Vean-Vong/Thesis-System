<!-- eslint-disable import/no-unresolved -->
<script setup>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions
import router from '@/router'

const form = reactive({
  data: {
    sediment: null,
    pre_carbon: null,
    uf_membrane: null,
    post_carbon: null,
  },
  options: {
    roles: [],
    filter: [],
  },
})

const refForm = ref()
const submitting = ref(false)

onMounted(() => {
  api.get('/filters').then(res => {
    form.options = res.data.data
  })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/filters', form.data)
      .then(res => {
        if (res.status === 200) router.back()
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Create New Filter Stock-in')"
    :submitting="submitting"
    @submit="onCreate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onCreate"
    >
      <VRow dense>
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.sediment"
            :label="$t('Sediment')"
            :rules="[rules.required]"
            :items="['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.pre_carbon"
            :label="$t('Pre-Carbon')"
            :rules="[rules.required]"
            :items="['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.uf_membrane"
            :label="$t('UF-Membrane')"
            :rules="[rules.required]"
            :items="['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.post_carbon"
            :label="$t('Post-Carbon')"
            :rules="[rules.required]"
            :items="['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.date"
            :rules="[rules.required]"
            :label="$t('Date')"
            type="date"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Filter Stock Create
  layout: default
  subject: Auth
  active: 'filter-stock'
</route>
