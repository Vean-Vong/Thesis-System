<script setup>
import api from "@/plugins/utilites"
import { reactive, ref, onMounted, nextTick } from "vue";
import { useRouter } from "vue-router";
const options = ref({
  employees: [],
  academic_years: [],
});
const router = useRouter();
const submitting = ref(false);

const form = reactive({
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
      .post("academic-classes-create", form)
      .then((res) => {
        router.push("/academic-class");
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};

onMounted(() => {
  api.post("academic-classes-option").then((res) => {
    options.value.teachers = res.data.teachers;
    options.value.academic_years = res.data.academic_years;
  });
});
</script>

<template>
  <VRow>
    <VCol cols="12" md="6" sm="8">
      <VCard title="បង្កើតថ្នាក់សិក្សា">
        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm class="mt-6" ref="refForm" lazy-validation @submit.prevent="onSubmit()">
            <VRow>
              <VCol md="12" cols="12">
                <VTextField
                  v-model="form.name"
                  label="ឈ្មោះ"
                  :rules="[(v) => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="12" cols="12">
                <v-autocomplete
                  :items="options.teachers"
                  item-value="id"
                  item-title="name"
                  v-model="form.teacher_id"
                  label="គ្រូបន្ទុកថ្នាក់"
                  :rules="[(v) => !!v || 'គ្រូបន្ទុកថ្នាក់ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="12" cols="12">
                <v-autocomplete
                  :items="options.academic_years"
                  item-value="id"
                  item-title="name"
                  v-model="form.academic_year_id"
                  label="ឆ្នាំសិក្សា"
                  :rules="[(v) => !!v || 'ឆ្នាំសិក្សា តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <!-- 👉 Form Actions -->
              <VCol cols="12" class="d-flex flex-wrap gap-4 justify-end">
                <VBtn type="submit" :loading="submitting" color="success"
                  ><VIcon>mdi-add</VIcon> រក្សាទុក</VBtn
                >
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
