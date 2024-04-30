<script setup>
import { onMounted } from "vue";
import router from "@/router";
import api from "@/plugins/utilites";

const model = ref({});
const loading = ref(false);

onMounted(() => {
  initData();
});

const initData = () => {
  const query = router.currentRoute.value.query;

  api.post("/patients-detail", { id: query.id }).then((res) => {
    model.value = res.data.data;
  });
};

const onSendToDoctor = () => {
  loading.value = true;
  api
    .post("/patients-update-status", {
      id: router.currentRoute.value.query.id,
      status: 2,
    })
    .then((res) => {
      if (res.status == 200) {
        model.value.current_stage = 2;
      }
    })
    .finally(() => {
      loading.value = false;
    });
};
</script>
<template>
  <AppFormDetailTemplate cols="10" md="10" :title="$t('Receptionist')">
    <VRow class="py-5">
      <VCol cols="12" md="12">
        <VRow>
          <VCol cols="12" md="6">
            <VList>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ $t("Code") }}:</div>
                </template>
                {{ model.code }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ $t("Name") }}:</div>
                </template>
                {{ model.name }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ $t("sex") }}:</div>
                </template>
                {{ model.sex_text }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ $t("age") }}:</div>
                </template>
                {{ model.age }}
              </VListItem>
            </VList>
          </VCol>
        </VRow>
      </VCol>
    </VRow>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: Patient Show
  layout: default
  action: read
  subject: Auth
  active: "transaction"
</route>
