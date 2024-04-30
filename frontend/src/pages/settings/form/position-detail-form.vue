<script setup>
import { onMounted } from "vue";
import router from "@/router";
import api from "@/plugins/utilites";

const { t } = useI18n();
const item = ref({});

onMounted(async () => {
  const query = await router.currentRoute.value.query;

  api.post("/positions-show", { id: query.id }).then((res) => {
    item.value = res.data.data;
  });
});
</script>
<template>
  <AppFormDetailTemplate cols="6" :title="$t('Detail Position')" class="py-0">
    <VRow class="py-5">
      <VCol cols="12" md="12">
        <VRow>
          <VCol cols="12" md="6">
            <VList>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ $t("Khmer Name") }}:</div>
                </template>
                {{ item.khmer_name }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ t("Latin Name") }}:</div>
                </template>
                {{ item.latin_name }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ t("Sort Number") }}:</div>
                </template>
                {{ item.sort }}
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
  title: Position Detail
  layout: default
  subject: Auth
  active: "position"
</route>
