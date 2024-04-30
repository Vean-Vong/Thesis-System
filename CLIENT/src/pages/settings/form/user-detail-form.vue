<script setup>
import { onMounted } from "vue";
import router from "@/router";
import api from "@/plugins/utilites";

const { t } = useI18n();

const form = reactive({
  data: {
    username: null,
    email: null,
    employee_id: null,
    role_ids: null,
  },
});

onMounted(() => {
  initForm();
});

const initForm = () => {
  const query = router.currentRoute.value.query;

  api.post("/users-show", { id: query.id }).then((res) => {
    form.data = res.data.data;
  });
};
</script>
<template>
  <AppFormDetailTemplate cols="10" :title="$t('Detail User')" class="py-0">
    <VRow class="py-5">
      <VCol cols="12" md="12">
        <VRow>
          <VCol cols="12">
            <VRow>
              <VCol cols="12" class="text-center" md="6">
                <VAvatar size="150">
                  <!-- photo Path -->
                  <VImg
                    cover
                    src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"
                  ></VImg>
                </VAvatar>
              </VCol>
            </VRow>
          </VCol>
          <VCol cols="12" md="6">
            <VList>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ $t("No") }}:</div>
                </template>
                {{ items.id }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ t("Name") }}:</div>
                </template>
                {{ items.username }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ t("Role") }}:</div>
                </template>
                <div v-for="item in items.roles">
                  {{ item.name }}
                </div>
              </VListItem>
            </VList>
          </VCol>
          <!-- <VCol cols="12" md="6">
                        <VRow no-gutters class="pa-0 d-none d-lg-flex">
                            <VCol cols="12" md="4">
                                <VList lines="one">
                                    <VListItem class="py-lg-0 px-lg-3 text-lg-end">
                                        {{ t('Permissions') }}: 
                                    </VListItem>
                                </VList>
                            </VCol>
                            <VCol cols="12" md="8">
                                <VList lines="one">
                                    <VListItem v-for="item in items.permissions" class="pa-lg-0">
                                        {{ t(ToSententceFn(item.name)) }}
                                    </VListItem>
                                </VList>
                            </VCol>
                        </VRow>
                        <VExpansionPanels variant="accordion" class="d-lg-none">
                            <VExpansionPanel :title="t('Permissions')">
                                <VExpansionPanelText class="pa-lg-0">
                                    <VListItem v-for="item in items.permissions" class="pa-lg-0">
                                        {{ t(ToSententceFn(item.name)) }}
                                    </VListItem>
                                </VExpansionPanelText>
                            </VExpansionPanel>
                        </VExpansionPanels>
                    </VCol> -->
        </VRow>
      </VCol>
    </VRow>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: User Detail
  layout: default
  subject: Auth
  active: "user"
</route>
