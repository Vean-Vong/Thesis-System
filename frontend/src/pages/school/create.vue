<script setup>
import api from "@/plugins/utilites"
import { reactive, ref, nextTick } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const submitting = ref(false);

const form = reactive({
  code: null,
  khmer_name: null,
  latin_name: null,
  sort: null,
});

const refForm = ref();
const onSubmit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("schools-create", form)
      .then((res) => {
        router.push("/school");
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};
</script>

<template>
  <VRow>
    <VCol cols="12" md="8">
      <VCard title="បង្កើតសាលារៀន">
        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm class="mt-6" ref="refForm" lazy-validation @submit.prevent="onSubmit()">
            <VRow>
              <VCol md="6" cols="12">
                <VTextField
                  v-model="form.code"
                  label="លេខកូដ"
                  :rules="[(v) => !!v || 'លេខកូដ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="6" cols="12">
                <VTextField
                  v-model="form.short_name"
                  label="ឈ្មោះខ្លី"
                  :rules="[(v) => !!v || 'ឈ្មោះខ្លី តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="12" cols="12">
                <VTextField
                  v-model="form.khmer_name"
                  label="ឈ្មោះ"
                  :rules="[(v) => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="6" cols="12">
                <VTextField v-model="form.latin_name" label="ឈ្មោះឡាតាំង" />
              </VCol>
              <VCol md="6" cols="12">
                <VTextField v-model="form.sort" label="លេខលំដាប់" />
              </VCol>
              <VCol cols="12" class="d-flex flex-wrap gap-4 justify-end">
                <VBtn type="submit" :loading="submitting" color="success"
                  ><VIcon>mdi-add</VIcon>&nbsp; រក្សាទុក</VBtn
                >
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
