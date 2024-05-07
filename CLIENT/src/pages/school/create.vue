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
      <VCard :title="$t('create school')">
        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm class="mt-6" ref="refForm" lazy-validation @submit.prevent="onSubmit()">
            <VRow>
              <VCol md="6" cols="12">
                <VTextField
                  v-model="form.code"
                  :label="$t('headers.id')"
                  :rules="[(v) => !!v || 'លេខកូដ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="6" cols="12">
                <VTextField
                  v-model="form.short_name"
                  :label="$t('headers.short name')"
                  :rules="[(v) => !!v || 'ឈ្មោះខ្លី តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="12" cols="12">
                <VTextField
                  v-model="form.khmer_name"
                  :label="$t('headers.name')"
                  :rules="[(v) => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol md="6" cols="12">
                <VTextField v-model="form.latin_name" :label="$t('latin_name')"/>
              </VCol>
              <VCol md="6" cols="12">
                <VTextField v-model="form.sort" :label="$t('headers.order number')" />
              </VCol>
              <VCol cols="12" class="d-flex flex-wrap gap-4 justify-end">
                <VBtn type="submit" :loading="submitting" color="success"
                  ><VIcon>mdi-add</VIcon>&nbsp; {{$t('Save changes')}}</VBtn
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
    title: schools-list
    layout: default
    subject: Auth
    active: 'school'
  </route>