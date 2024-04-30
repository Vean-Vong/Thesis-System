<script setup>
import { onMounted } from "vue";
import router from "@/router";
import api from "@/plugins/utilites";

const model = ref({});

onMounted(() => {
  initData();
});

const initData = () => {
  const query = router.currentRoute.value.query;
  api.post("/patients-detail", { id: query.id }).then((res) => {
    model.value = res.data.data;
  });
};

const onCheck = () => {
  router.push({
    name: "transactions-check-create",
    query: { id: router.currentRoute.value.query.id },
  });
};
</script>
<template>
  <AppFormDetailTemplate cols="10" md="10" :title="$t('Receptionist')">
    <template #btnSubmit>
      <AppSubmitButton :title="$t('Check')" @click="onCheck"></AppSubmitButton>
      <AppSubmitButton :title="$t('Stay')"></AppSubmitButton>
      <AppSubmitButton :title="$t('Finish')"></AppSubmitButton>
    </template>
    <VRow class="py-5">
      <VCol cols="12" md="12">
        <VRow>
          <VCol cols="12" md="6">
            <VListItem class="text-h5">
              {{ $t("Patient") }}
            </VListItem>
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
            <VListItem class="text-h5">
              {{ $t("Life Sign") }}
            </VListItem>
            <VRow noGutters>
              <VCol cols="6" md="4">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <div class="me-3">{{ $t("BP") }}:</div>
                    </template>
                    {{ model.bp }}
                  </VListItem>
                </VList>
              </VCol>
              <VCol cols="6" md="4">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <div class="me-3">{{ $t("FR") }}:</div>
                    </template>
                    {{ model.fr }}
                  </VListItem>
                </VList>
              </VCol>
              <VCol cols="6" md="4">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <div class="me-3">{{ $t("GCS") }}:</div>
                    </template>
                    {{ model.gcs }}
                  </VListItem>
                </VList>
              </VCol>
              <VCol cols="6" md="4">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <div class="me-3">{{ $t("PULSE") }}:</div>
                    </template>
                    {{ model.pulse }}
                  </VListItem>
                </VList>
              </VCol>
              <VCol cols="6" md="4">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <div class="me-3">{{ $t("RR") }}:</div>
                    </template>
                    {{ model.rr }}
                  </VListItem>
                </VList>
              </VCol>
              <VCol cols="6" md="4">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <div class="me-3">{{ $t("T") }}<sup>o</sup>:</div>
                    </template>
                    {{ model.to }}
                  </VListItem>
                </VList>
              </VCol>
              <VCol cols="6" md="4">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <div class="me-3">SpO<sub>2</sub>:</div>
                    </template>
                    {{ model.spo2 }}
                  </VListItem>
                </VList>
              </VCol>
            </VRow>
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
