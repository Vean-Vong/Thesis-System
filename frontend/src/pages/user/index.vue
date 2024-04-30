<script setup>
import api from "@/utils/utilites";
import { onMounted, reactive, ref, watch } from "vue";
import _ from "lodash";
import { useRouter } from "vue-router";
import User from "../../class/User";
const currentPage = ref(1);
import { useAuthStore } from "@/store/module/auth.module";
const headers = ["ឈ្មោះ", "អុីម៉ែល", "តួនាទី", "សកម្មភាព"];

const form = reactive({
  filter: null,
});

const data = ref([]);
const numPages = ref(0);
const perPage = ref(10);
const router = useRouter();
const confirmDialog = ref(false);
const user_id = ref("");
const action = ref(null);
const loading = ref(false);
const total = ref(null);
const to = ref(null);
const from = ref(null);
const search = ref(null);
const store = useAuthStore();

const user = computed(() => {
  const data = {
    user: store?._user,
  };
  return new User(data);
});
//action 1 = delete, 2 = reset pw

const q = () => {
  fetchData();
};

const fetchData = () => {
  loading.value = true;
  api
    .post(`users-list`, {
      perPage: perPage.value,
      page: currentPage.value,
      search: search.value,
    })
    .then((res) => {
      data.value = res.data?.data?.data;
      total.value = res.data?.data?.total;
      from.value = res.data?.data?.from;
      to.value = res.data?.data?.to;
      numPages.value = Math.ceil(res.data.data.total / perPage.value);
    })
    .finally(() => {
      loading.value = false;
    });
};

watch(currentPage, (newValue, oldValue) => {
  if (newValue) {
    fetchData();
  }
});

const show = (id) => {
  router.push(`/user/${id}`);
};

const onChangePassword = (id) => {
  action.value = 2;
  user_id.value = id;
  confirmDialog.value = true;
};

const onDelete = (id) => {
  action.value = 1;
  user_id.value = id;
  confirmDialog.value = true;
};

const confirmAction = () => {
  if (action.value === 1) {
    api
      .post("users-delete", {
        id: user_id.value,
      })
      .then((res) => {
        fetchData();
        confirmDialog.value = false;
        user_id.value = "";
        action.value = null;
      });
  } else if (action.value === 2) {
    api
      .post("users-reset-pw", {
        id: user_id.value,
      })
      .then((res) => {
        fetchData();
        confirmDialog.value = false;
        user_id.value = "";
        action.value = null;
      });
  }
};

onMounted(() => {
  fetchData();
});
</script>

<template>
  <div>
    <VCard title="តារាងអ្នកប្រើប្រាស់" class="mb-5">
      <VDivider />
      <VCard-text>
        <VRow justify="start">
          <VCol cols="12" md="4">
            <VTextField
              v-model="search"
              placeholder="ស្វែងរក"
              append-inner-icon="mdi-search"
              @keypress.enter="q"
              @click:append-inner="q"
            />
          </VCol>
          <VCol cols="12" md="4"></VCol>
          <v-col cols="6" md="4" class="text-end">
            <VBtn
              size="large"
              variant="elevated"
              prepend-icon="mdi-plus"
              color="info"
              to="user/create"
            >
              បញ្ជូលថ្មី
            </VBtn></v-col
          >
        </VRow>

        <VTable
          :headers="headers"
          :items="data"
          item-key="fullName"
          class="table-rounded"
        >
          <thead>
            <tr>
              <th v-for="header in headers" :key="header">
                {{ header }}
              </th>
            </tr>
          </thead>

          <tbody>
            <td v-if="loading" :colspan="headers.length">
              <v-progress-linear indeterminate class="line"></v-progress-linear>
            </td>
            <tr v-if="loading && data.length === 0">
              <td :colspan="headers.length" class="text-center">
                <div class="text-subtitle-2">កំពុងដំណើរការ...</div>
              </td>
            </tr>
            <tr v-if="!loading && data.length === 0">
              <td :colspan="headers.length" class="text-caption text-center">
                មិនមានផ្ទុកទិន្នន័យ...
              </td>
            </tr>
            <tr v-for="row in data" :key="row.id">
              <td v-text="row.username" />
              <td v-text="row.email" />
              <td v-text="row.roles[0]?.name" />
              <td>
                <v-btn @click="show(row.id)" color="white" elevation="0" flat>
                  <v-icon color="success">mdi-square-edit-outline</v-icon>
                  <v-tooltip activator="parent" location="bottom"> កែប្រែ </v-tooltip>
                </v-btn>
                <v-btn
                  :disabled="row.id == user.id"
                  @click="onDelete(row.id)"
                  color="white"
                  elevation="0"
                  flat
                >
                  <v-icon color="error">mdi-trash</v-icon>
                  <v-tooltip activator="parent" location="bottom"> លុប </v-tooltip>
                </v-btn>

                <v-btn @click="onChangePassword(row.id)" color="white" elevation="0" flat>
                  <v-icon color="warning">mdi-key</v-icon>
                  <v-tooltip activator="parent" location="bottom">
                    ផ្លាស់ប្តូរលេខសម្ងាត់
                  </v-tooltip>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </VTable>
      </VCard-text>
      <VCard-actions>
        <v-row>
          <v-col cols="12" lg="6" md="12" sm="12" xs="12" class="mt-3 text-center">
            <span v-if="!loading"
              >{{ from }} - {{ to }} {{ total === 0 ? "" : `of ${total}` }}</span
            ></v-col
          >
          <v-col cols="12" lg="6" md="12" sm="12" xs="12">
            <VPagination
              v-model="currentPage"
              class="ml-auto"
              :length="numPages"
              :total-visible="10"
            />
          </v-col>
        </v-row>
      </VCard-actions>
    </VCard>

    <v-dialog v-model="confirmDialog" style="max-width: 500px" persistent>
      <v-card>
        <v-card-text>
          {{
            action === 2
              ? "តើអ្នកប្រាកដក្នុងការផ្លាស់ប្តូលេខសម្ងាត់នេះទេ?"
              : "តើអ្នកប្រាកដក្នុងការលុបអ្នកប្រើប្រាស់នេះទេ?"
          }}
        </v-card-text>
        <v-card-actions class="ml-auto">
          <v-btn color="error" @click="confirmDialog = false">បោះបង់</v-btn>
          <v-btn color="success" @click="confirmAction">យល់ព្រម</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
