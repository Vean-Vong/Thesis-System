<script setup>
import api from '@/plugins/utilites'
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()
const options = ref({
  teachers: [],
  levels: [],
  times: [],
  rooms: [],
  academic_years: [],
  class_type: [
    { id: 1, name: 'កាត់ដេ' },
    { id: 2, name: 'អង់គ្លេស' },
    { id: 3, name: 'កុំព្យូទ័រ' },
  ]
})
const submitting = ref(false)

const form = reactive({
  name: null,
  teacher_id: null,
  room_id: null,
  time_id: null,
  level_id: null,
  academic_year_id: null,
  type: null
})

const refForm = ref()

const fetchOptions = async () => {
  const res = await api.post('academic-classes-option')
  options.value.teachers = res.data.teachers
  options.value.levels = res.data.levels
  options.value.times = res.data.times
  options.value.rooms = res.data.rooms
  options.value.academic_years = res.data.academic_years
}

const fetchData = async (id) => {
  const res = await api.post('academic-classes-show', { id })
  Object.assign(form, res.data.model)
}

const onSubmit = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    const endpoint = form.id ? `academic-classes-update/${form.id}` : 'academic-classes-create'
    const method = form.id ? 'put' : 'post'
    api[method](endpoint, form)
      .then(() => {
        router.push('/academic-class')
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

onMounted(async () => {
  await fetchOptions()
  if (route.params.id) {
    await fetchData(route.params.id)
  }
})
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="6"
      sm="8"
    >
      <VCard :title="route.params.id ? $t('edit class') : $t('create class')">
        <VDivider />
        <VCardText>
          <VForm
            class="mt-6"
            ref="refForm"
            lazy-validation
            @submit.prevent="onSubmit"
          >
            <VRow>
              <VCol
                md="5"
                cols="12"
              >
                <VTextField
                  v-model="form.name"
                  :label="$t('headers.name')"
                />
              </VCol>
              <VCol
                md="4"
                cols="12"
              >
                <VAutocomplete
                  :items="options.class_type"
                  item-value="id"
                  item-title="name"
                  v-model="form.type"
                  :label="$t('ប្រភេទថ្នាក់រៀន')"
                  :rules="[v => !!v || 'ប្រភេទថ្នាក់រៀន តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                md="3"
                cols="12"
              >
                <VAutocomplete
                  :items="options.academic_years"
                  item-value="id"
                  item-title="name"
                  v-model="form.academic_year_id"
                  :label="$t('academic_year')"
                  :rules="[v => !!v || 'ឆ្នាំសិក្សា តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                md="4"
                cols="12"
              >
                <VAutocomplete
                  :items="options.teachers"
                  item-value="id"
                  item-title="name"
                  v-model="form.teacher_id"
                  :label="$t('class teacher')"
                  :rules="[v => !!v || 'គ្រូបន្ទុកថ្នាក់ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                md="4"
                cols="12"
              >
                <VAutocomplete
                  :items="options.rooms"
                  item-value="id"
                  item-title="room"
                  v-model="form.room_id"
                  :label="$t('room')"
                  :rules="[v => !!v || 'បន្ទប់ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                md="4"
                cols="12"
                v-if="form.type == 2 || form.type == 3"
              >
                <VAutocomplete
                  :items="options.times"
                  item-value="id"
                  item-title="time"
                  v-model="form.time_id"
                  :label="$t('time')"
                  :rules="[v => !!v || 'ម៉ោង តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                md="4"
                cols="12"
                v-if="form.type == 2 || form.type == 3"
              >
                <VAutocomplete
                  :items="options.levels"
                  item-value="id"
                  item-title="level"
                  v-model="form.level_id"
                  :label="$t('level')"
                  :rules="[v => !!v || 'កម្រិត តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                cols="12"
                class="d-flex flex-wrap gap-4 justify-end"
              >
                <VBtn
                  type="submit"
                  :loading="submitting"
                  color="success"
                ><VIcon>mdi-content-save</VIcon> {{ $t('Save changes') }}</VBtn>
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
  title: academic-class
  layout: default
  subject: Auth
  active: 'academic-class'
</route>
