<script setup>
import api from "@/plugins/utilites"
import { reactive, ref, onMounted, nextTick } from "vue";
import { useRoute, useRouter } from "vue-router";

const router = useRouter();
const { params } = useRoute();
const submitting = ref(false);
const options = ref({
  employees: [],
  academic_years: [],
});
const form = reactive({
  id: params.id,
  name: null,
  teacher_id: null,
  academic_year_id: null,
});

const refForm = ref();
const onSubmit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("academic-classes-update", form)
      .then((res) => {
        router.push("/academic-class");
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};
const fetchData = () => {
  
  api.post("academic-classes-show", {
      id: params.id,
    })
    .then((res) => {
      form.name = res.data.model.name;
      form.teacher_id = res.data.model.teacher_id;
      form.level_id = res.data.model.level_id;
      form.room_id = res.data.model.room_id;
      form.time_id = res.data.model.time_id;
      form.type = res.data.model.type;
      form.academic_year_id = res.data.model.academic_year_id;
    });

  api.post("academic-classes-option").then((res) => {
    options.value.teachers = res.data.teachers;
    options.value.academic_years = res.data.academic_years;
    options.value.teachers = res.data.teachers
    options.value.levels = res.data.levels
    options.value.times = res.data.times
    options.value.rooms = res.data.rooms
    options.value.academic_years = res.data.academic_years
    options.value.class_type = [
      { id: 1, name: 'កាត់ដេ' },
      { id: 2, name: 'អង់គ្លេស' },
      { id: 3, name: 'កុំព្យូទ័រ' },
    ]
  });
  
};

onMounted(() => {
  fetchData();
});
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="6"
      sm="8"
    >
      <VCard :title="$t('update class')">
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
                md="5"
                cols="12"
              >
                <VTextField
                  v-model="form.name"
                  :label="$t('headers.name')"
                  :rules="[v => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
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
              
              <!-- 👉 Form Actions -->
              <VCol
                cols="12"
                class="d-flex flex-wrap gap-4 justify-end"
              >
                <VBtn
                  type="submit"
                  :loading="submitting"
                  color="success"
                  ><VIcon>mdi-content-save</VIcon> {{ $t('Save changes') }}</VBtn
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
    title: academic-classes-update 
    layout: default
    subject: Auth
    active: 'academic-classes-update'
  </route>
