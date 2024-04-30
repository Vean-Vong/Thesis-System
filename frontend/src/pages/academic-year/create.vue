<script setup>
import avatar1 from "@/assets/images/avatars/avatar-1.png";
import api from "@/utils/utilites";
import { reactive, ref, onMounted, nextTick } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const submitting = ref(false);

const form = reactive({
  name: null,
  start_date: "",
  end_date: "",
  is_active: false,
});

const refForm = ref();
const onSubmit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("academic-years-create", form)
      .then((res) => {
        router.push("/academic-year");
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};

onMounted(() => {});
</script>

<template>
  <VRow>
    <VCol cols="12" md="10" sm="10">
      <VCard title="បង្កើតឆ្នាំសិក្សា">
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
              <VCol md="6" cols="12">
                <VTextField
                  v-model="form.start_date"
                  label="ថ្ងៃចាប់ផ្តើម"
                  type="date"
                  :rules="[(v) => !!v || 'ថ្ងៃចាប់ផ្តើម តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="6" cols="12">
                <VTextField
                  v-model="form.end_date"
                  label="ថ្ងៃបញ្ចប់"
                  type="date"
                  :rules="[(v) => !!v || 'ថ្ងៃបញ្ចប់ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="12" cols="12" class="ml-1">
                <v-checkbox v-model="form.is_active" :val="1" label="កំពុងប្រតិបត្តិការ">
                </v-checkbox>
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
