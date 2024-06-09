<script setup>
import api from '@/plugins/utilites'
import {  ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const { params } = useRoute()
const submitting = ref(false)

const form = ref({})

const refForm = ref()
const onSubmit = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('time-update', form.value)
      .then(res => {
        router.push('/time')
      })
      .finally(() => {
        submitting.value = false
      })
  }
}
const fetchData = () => {
  api
    .post('time-show', {
      id: params.id,
    })
    .then(res => {
      form.value = res.data.model
    })
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="6"
      sm="10"
    >
      <VCard :title="$t('time_update')">
        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm
            class="mt-6"
            ref="refForm"
            lazy-validation
            @submit.prevent="onSubmit()"
          >
            <VRow>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="form.time"
                  :label="$t('time')"
                  :rules="[v => !!v || 'បន្ទប់ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <!-- 👉 Form Actions -->
              <VCol
                cols="12"
                class="d-flex flex-wrap gap-4 justify-end"
              >
                <VBtn
                  type="submit"
                  :loading="submitting"
                  color="success"
                  ><VIcon>mdi-add</VIcon> {{ $t('Save changes') }}</VBtn
                >
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
<route lang="yaml">
meta:
  title: time-update
  layout: default
  subject: Auth
  active: 'time-update'
</route>
