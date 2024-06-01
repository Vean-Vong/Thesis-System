<script setup>
import api from "@/plugins/utilites"
import { reactive, ref, onMounted, nextTick } from "vue";
import { useRoute, useRouter } from "vue-router";

const router = useRouter();
const { params } = useRoute();
const submitting = ref(false);

const form = reactive({
  room: "",
  is_active: false,
});

const refForm = ref();
const onSubmit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("room-update", form)
      .then((res) => {
        router.push("/room");
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};
const fetchData = () => {
  api
    .post("room-show", {
      id: params.id,
    })
    .then((res) => {
      form.room = res.data.model.room;
      // form.date = res.data.model.date;
      form.is_active = res.data.model.is_active == 1 ? true : false;
    });
};

onMounted(() => {
  fetchData();
});
</script>

<template>
  <VRow>
    <VCol cols="12" md="6" sm="10">
      <VCard :title="$t('room_update')">
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
              <!-- <VCol md="6" cols="12">
                <VTextField
                  v-model="form.date"
                  label="date"
                  type="date"
                  :rules="[(v) => !!v || 'ថ្ងៃ តម្រូវឱ្យបំពេញ']"
                />
              </VCol> -->
              <VCol md="12" cols="12" class="ml-1">
                <v-checkbox v-model="form.is_active" :val="1" :label="$t('ongoing')">
                </v-checkbox>
              </VCol>
              <!-- 👉 Form Actions -->
              <VCol cols="12" class="d-flex flex-wrap gap-4 justify-end">
                <VBtn type="submit" :loading="submitting" color="success"
                  ><VIcon>mdi-add</VIcon> {{$t('Save changes')}}</VBtn
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
    title: room-update 
    layout: default
    subject: Auth
    active: 'room-update'
  </route>