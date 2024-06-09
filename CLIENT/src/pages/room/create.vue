<script setup>
import api from "@/plugins/utilites"
import { reactive, ref, onMounted, nextTick } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const submitting = ref(false);

const form = reactive({
  room:"",
  is_active: false,
});

const refForm = ref();
const onSubmit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("room-create", form)
      .then((res) => {
        router.push("/room");
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
    <VCol cols="12" md="6" sm="10">
      <VCard :title="$t('room_create')">
        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm class="mt-6" ref="refForm" lazy-validation @submit.prevent="onSubmit()">
            <VRow>
              <VCol md="6" cols="12">
                <VTextField
                  v-model="form.room"
                  :label="$t('room')"
                  :rules="[(v) => !!v || 'បន្ទប់ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <!-- 👉 Form Actions -->
              <VCol cols="12" class="d-flex flex-wrap gap-4 justify-end">
                <VBtn type="submit" :loading="submitting" color="success"
                  ><VIcon class="me-2">mdi-content-save-all</VIcon> {{$t('Save changes')}}</VBtn
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
    title: room-create 
    layout: default
    subject: Auth
    active: 'room-create'
  </route>