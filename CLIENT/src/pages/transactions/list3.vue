<script setup>
import PatientPendingTable from "./components/PatientPendingTable.vue";
import api from "@/plugins/utilites";
import router from "@/router";
import { onMounted } from "vue";
import { useAuthStore } from "@/plugins/auth.module";
import ConfirmDialog from "@/components/ConfirmDialog.vue";
const user = useAuthStore().user;
const { t } = useI18n();
const items = ref([]);
const loading = ref(false);

const search = ref(null);
const delete_item = ref(null);
const deleting = ref(false);
const dialog = ref(false);

const meta = ref({
  current_page: 1,
  from: 1,
  last_page: 1,
  per_page: 15,
  to: 1,
  total: 0,
});

const initData = () => {
  loading.value = true;
  api
    .post("/patients-list", {
      page: meta?.current_page,
      limit: meta?.per_page,
      search: search.value,
      current_stage: 3,
    })
    .then((res) => {
      items.value = res.data.data.data;
      meta.value = res.data.data.meta;
    })
    .finally(() => {
      loading.value = false;
    });
};

onMounted(() => {
  initData();
});

const headers = [
  {
    title: t("No"),
    key: "no",
    align: "left",
    sortable: false,
    minWidth: "10px",
    maxWidth: "15px",
  },
  {
    title: t("Id Card"),
    key: "id_card",
    align: "left",
    sortable: false,
    minWidth: "15px",
    maxWidth: "25px",
  },
  {
    title: t("Name"),
    key: "name",
    align: "left",
    sortable: false,
  },
  {
    title: t("Sex"),
    key: "sex_text",
    align: "center",
    sortable: false,
  },
  {
    title: t("age"),
    key: "age",
    align: "center",
    sortable: false,
  },
  {
    title: t("Phone"),
    key: "phone",
    align: "center",
    sortable: false,
  },
];

const viewCallback = (item) => {
  router.push({ name: "transactions-preview2", query: { id: item } });
};

const deleteCallback = (item) => {
  dialog.value = true;
  delete_item.value = item;
};

const editCallback = (item) => {
  router.push({
    name: "patients-edit",
    query: { uuid: item },
  });
};

const updateCallback = (item) => {
  meta.current_page = item.page;
  meta.per_page = item.limit;
  initData();
};

const onSearch = () => {
  initData();
};

const cancelCallback = () => {
  dialog.value = false;
  delete_item.value = null;
};

const confirmDeleteCallback = () => {
  deleting.value = true;
  api
    .post("patients-delete", { id: delete_item.value })
    .then((res) => {
      if (res.status == 200) {
        initData();
      }
    })
    .finally(() => {
      deleting.value = false;
      delete_item.value = null;
      dialog.value = false;
    });
};
</script>

<template>
  <ConfirmDialog
    v-model="dialog"
    @on-cancel="cancelCallback"
    @on-confirm-delete="confirmDeleteCallback"
    :deleting="deleting"
  >
  </ConfirmDialog>
  <PatientPendingTable
    :headers="headers"
    :items="items"
    :items-per-page="meta?.per_page"
    :items-length="meta?.total"
    :from="meta?.from"
    :current-page="meta?.current_page"
    :to="meta?.to"
    @on-view="viewCallback"
    :tableTitle="$t('List of Patient')"
    cols="12"
    :loading="loading"
  >
    <template #forFilter>
      <VRow class="justify-start" dense>
        <VCol cols="8" md="3">
          <AppTextField
            :placeholder="$t('Search')"
            @keyup.enter="onSearch"
            v-model="search"
          />
        </VCol>
        <VCol cols="4" md="2">
          <AppSearchButton
            @click="onSearch"
            :title="$t('Search')"
            :showIcon="1"
          ></AppSearchButton>
        </VCol>
      </VRow> </template
  ></PatientPendingTable>
</template>

<route lang="yaml">
meta:
  title: Patient
  layout: default
  subject: Auth
  active: "transaction"
</route>
