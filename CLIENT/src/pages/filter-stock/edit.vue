<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'

const route = useRoute()
const router = useRouter()
const refForm = ref()
const submitting = ref(false)
const filterId = route.query.id // Get the ID from the URL

const form = reactive({
  data: {
    sediment: null,
    pre_carbon: null,
    uf_membrane: null,
    post_carbon: null,
  },
})

onMounted(() => {
  if (filterId) {
    api.get(`/filters/${filterId}`).then(res => {
      form.data = res.data.data // Load data from API
    })
  }
})

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .put(`/filters/${filterId}`, form.data) // Send update request
      .then(res => {
        if (res.status === 200) {
          router.push({ name: 'filter-stock' }) // Redirect after update
        }
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
    :title="$t('Update Stock-in')"
    :submitting="submitting"
    @submit="onUpdate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onUpdate"
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
  title: Filter Stock Edit
  layout: default
  subject: Auth
  active: 'filter-stock'
</route>
