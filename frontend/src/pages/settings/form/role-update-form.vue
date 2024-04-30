<script setup>
import api from "@/plugins/utilites";
import router from "@/router";

const { t } = useI18n();

const form = ref({});
const refForm = ref();

const permissions = ref([]);

onMounted(() => {
  const query = router.currentRoute.value.query;

  api
    .post("/roles-show", { id: query.id })
    .then((res) => {
      form.value = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });

  api
    .post("/permissions-list")
    .then((res) => {
      permissions.value = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });
});

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    api
      .post("/roles-update", form.value)
      .then((res) => {
        if (res.status == 200) router.back();
      })
      .catch((err) => {
        console.log(err);
      });
  }
};

const selectAll = (permissions) => {
  for (const permission of permissions) {
    if (!form.value.permissions.includes(permission.name)) {
      form.value.permissions.push(permission.name);
    }
  }
};

const deselectAll = (permissions) => {
  for (const permission of permissions) {
    if (form.value.permissions.includes(permission.name)) {
      form.value.permissions.splice(form.value.permissions.indexOf(permission.name), 1);
    }
  }
};

const toSentenceFunction = (p) => {
  return p.charAt(0).toUpperCase() + p.substr(1);
};
</script>

<template>
  <AppFormUpdateTemplate cols="10" @submit="onUpdate" :title="$t('Update Role')">
    <VForm class="px-3" ref="refForm" lazy-validation>
      <VRow>
        <VCol cols="12">
          <VRow no-gutters>
            <VCol cols="12" md="6">
              <AppTextField
                id="name"
                :rules="[(v) => !!v || $t('Name') + $t('required')]"
                v-model="form.name"
                required="true"
                :label="$t('Name')"
              />
            </VCol>
          </VRow>
        </VCol>

        <VCol cols="12">
          <VCard>
            <VCardText>
              <p class="text-lg">{{ t("Select") }}{{ t("Permissions") }}</p>
            </VCardText>
            <VCardActions>
              <VExpansionPanels multiple>
                <VExpansionPanel v-for="(it, idx) in permissions" :key="idx">
                  <template v-for="(itx, g) in it" :key="g">
                    <VExpansionPanelTitle>
                      {{ $t(toSentenceFunction(g)) }}
                    </VExpansionPanelTitle>
                    <VExpansionPanelText>
                      <VSheet
                        class="d-flex flex-column flex-lg-row align-center flex-wrap"
                      >
                        <div class="me-4 my-lg-0 my-4">
                          <VRow no-gutters>
                            <VCol cols="12" md="6" sm="6">
                              <VBtn
                                variant="flat"
                                size="x-small"
                                color="primary"
                                @click="selectAll(itx)"
                              >
                                {{ $t("Select All") }}
                              </VBtn>
                            </VCol>
                            <VCol cols="12" md="6" sm="6">
                              <VBtn
                                variant="flat"
                                size="x-small"
                                color="error"
                                @click="deselectAll(itx)"
                              >
                                {{ $t("Deselect All") }}
                              </VBtn>
                            </VCol>
                          </VRow>
                        </div>
                        <VCheckbox
                          v-for="(permission, p) in itx"
                          :key="p"
                          v-model="form.permissions"
                          :label="$t(permission.name)"
                          :value="permission.name"
                          density="compact"
                          class="pa-0 ma-0 text-caption flex-1 list-width"
                          hide-details
                          single-line
                        />
                      </VSheet>
                    </VExpansionPanelText>
                  </template>
                </VExpansionPanel>
              </VExpansionPanels>
            </VCardActions>
          </VCard>
        </VCol>
      </VRow>
    </VForm>
  </AppFormUpdateTemplate>
</template>

<route lang="yaml">
meta:
  title: Role Edit
  layout: default
  subject: Auth
  active: "role"
</route>

<style scoped>
.list-width {
  width: 25%;
}
@media screen and (max-width: 768px) and (min-width: 320px) {
  .list-width {
    width: 100%;
  }
}
</style>
