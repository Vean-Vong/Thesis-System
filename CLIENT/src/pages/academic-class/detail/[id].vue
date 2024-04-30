<script setup>
import api from "@/plugins/utilites"
import { reactive, ref, onMounted, nextTick } from "vue";
import { useRoute, useRouter } from "vue-router";
import User from "../../../class/User";
import { useAuthStore } from "@/plugins/auth.module";
const router = useRouter();
const { params } = useRoute();
const action = ref(null);
const submitting = ref(false);
const model = ref({});
const search = ref("");
const currentPage = ref(1);
const data = ref([]);
const students = ref([]);
const numPages = ref(0);
const perPage = ref(10);
const loading = ref(false);
const loading2 = ref(false);
const adding_student = ref(false);
const total = ref(null);
const to = ref(null);
const from = ref(null);
const refForm = ref();
const delete_item = ref(null);
const confirmDialog = ref(false);
const dialog_add_student = ref(false);
const headers = ["", "អត្ថលេខ", "ឈ្មោះ", "ភេទ", "ថ្ងៃខែឆ្នាំកំណើត"];
const headers2 = ["អត្ថលេខ", "ឈ្មោះ", "ភេទ", "ថ្ងៃខែឆ្នាំកំណើត", "សកម្មភាព"];
const selected_students = ref([]);
const listing = ref(false);
const store = useAuthStore();

const fetchData = () => {
  api
    .post("academic-classes-detail", {
      id: params.id,
    })
    .then((res) => {
      model.value = res.data.model;
    });
  listingStudent();
};

const user = computed(() => {
  const data = {
    user: store?._user,
  };
  return new User(data);
});

const searchStudent = () => {
  loading.value = true;
  api
    .post("academic-classes-search-student", {
      search: search.value,
      perPage: perPage.value,
      page: currentPage.value,
      academic_year_id: model.value.academic_year.id,
    })
    .then((response) => {
      data.value = response.data.data.data;
      total.value = response.data.data.total;
      from.value = response.data.data.from;
      to.value = response.data.data.to;
      numPages.value = Math.ceil(response.data.data.total / perPage.value);
    })
    .finally(() => {
      loading.value = false;
    });
};

const onConfirmAddStudent = () => {
  adding_student.value = true;
  api
    .post("academic-classes-add-student", {
      academic_class_id: model.value.id,
      student_ids: selected_students.value,
    })
    .then((res) => {
      listingStudent();
      dialog_add_student.value = false;
    })
    .catch((res) => {
      dialog_add_student.value = false;
    })
    .finally(() => {
      adding_student.value = false;
    });
};

const q = () => {
  searchStudent();
};

const qStudent = () => {
  listingStudent();
};

const onAddStudent = () => {
  searchStudent();
  dialog_add_student.value = true;
};

watch(dialog_add_student, (newValue) => {
  search.value = null;
});

const onDelete = (id) => {
  action.value = 1;
  delete_item.value = id;
  confirmDialog.value = true;
};

const onMoveStudent = (id) => {
  action.value = 2;
  delete_item.value = id;
  confirmDialog.value = true;
};

const confirmAction = () => {
  if (action.value === 1) {
    api
      .post("academic-classes-remove-student", {
        id: delete_item.value,
      })
      .then((res) => {
        listingStudent();
      })
      .finally(() => {
        confirmDialog.value = false;
        delete_item.value = null;
      });
  } else if (action.value === 2) {
    api
      .post("academic-classes-move-student", {
        id: delete_item.value,
      })
      .then((res) => {
        listingStudent();
      })
      .finally(() => {
        confirmDialog.value = false;
        delete_item.value = null;
      });
  }
};

const listingStudent = () => {
  listing.value = true;
  loading2.value = true;
  api
    .post("academic-classes-list-student", {
      search: search.value,
      academic_class_id: params.id,
    })
    .then((response) => {
      students.value = response.data.data;
    })
    .finally(() => {
      loading2.value = false;
    });
};

const addScore = () => {
  router.push("/academic-class/detail/score/" + params.id);
};

const listingScore = () => {
  router.push("/academic-class/detail/score/" + params.id + "_" + "c");
};

const onAddAttendance = () => {
  router.push("/academic-class/detail/attendance/" + params.id);
};

const listingAttendance = () => {
  router.push("/academic-class/detail/attendance/" + params.id + "_" + "c");
};

onMounted(() => {
  fetchData();
});
</script>

<template>
  <div>
    <VRow>
      <VCol cols="12" md="12" sm="12">
        <VCard :title="`ថ្នាក់ទី ${model.name} ឆ្នាំសិក្សា ${model.academic_year?.name}`">
          <VDivider />
          <v-btn
            class="mt-5 mx-5"
            color="secondary"
            variant="outlined"
            @click="$router.go(-1)"
            ><v-icon>mdi-arrow-back</v-icon>&nbsp;ថយក្រោយ</v-btn
          >
          <VCardText>
            <v-row class="my-5 justify-end">
              <v-btn class="mx-5" color="grey" @click="listingScore()"
                ><v-icon>mdi-eye</v-icon>&nbsp;បញ្ជីនៃពិន្ទុ</v-btn
              >
              <v-btn color="grey" @click="listingAttendance()"
                ><v-icon>mdi-eye</v-icon>&nbsp;បញ្ជីនៃអវត្តមាន</v-btn
              >
            </v-row>
            <v-row class="my-5 justify-end">
              <v-btn class="mx-5" color="success" @click="addScore()"
                ><v-icon>mdi-add</v-icon>&nbsp;បញ្ចូលពិន្ទុ</v-btn
              >
              <v-btn color="success" @click="onAddAttendance()"
                ><v-icon>mdi-add</v-icon>&nbsp;បញ្ចូលអវត្តមាន</v-btn
              >
            </v-row>
          </VCardText>
          <v-card-text v-if="listing">
            <div class="text-center font-weight-bold text-h6">បញ្ជីនៃសិស្ស</div>
            <VDivider class="my-5" :thickness="1" />
            <v-row>
              <v-col cols="4" md="4" lg="4" sm="4">
                <VTextField
                  v-model="search"
                  placeholder="ស្វែងរក"
                  append-inner-icon="mdi-search"
                  @keypress.enter="qStudent"
                  @click:append-inner="qStudent"
                />
              </v-col>
              <v-spacer />
              <v-col cols="4" md="4" lg="4" sm="4" align="end">
                <v-btn
                  v-if="user?.can('manage_student_class_access')"
                  color="success"
                  @click="onAddStudent()"
                  ><v-icon>mdi-add</v-icon>&nbsp;បញ្ចូលសិស្ស</v-btn
                >
              </v-col>
            </v-row>
            <VTable :headers="headers2" :items="students" class="table-rounded">
              <thead>
                <tr>
                  <th v-for="header in headers2" :key="header">
                    {{ header }}
                  </th>
                </tr>
              </thead>

              <tbody>
                <td v-if="loading2" :colspan="headers2.length + 1">
                  <v-progress-linear indeterminate class="line"></v-progress-linear>
                </td>
                <tr v-if="loading2 && headers2.length === 0">
                  <td :colspan="headers.length" class="text-center">
                    <div class="text-subtitle-2">កំពុងដំណើរការ...</div>
                  </td>
                </tr>
                <tr v-if="!loading && students.length === 0">
                  <td :colspan="headers.length + 1" class="text-subtitle-2 text-center">
                    មិនមានផ្ទុកទិន្នន័យ...
                  </td>
                </tr>
                <tr v-for="row in students" :key="row.id">
                  <td v-text="row.student?.code" />
                  <td v-text="row.student?.name" />
                  <td v-text="row.student?.sex_text" />
                  <td v-text="row.student?.dob" />
                  <td>
                    <div v-if="row.status == 1">
                      <v-btn @click="onDelete(row.id)" color="white" elevation="0" flat>
                        <v-icon color="error">mdi-trash</v-icon>
                        <v-tooltip activator="parent" location="bottom">
                          ដកសិស្សចេញ
                        </v-tooltip>
                      </v-btn>
                      <v-btn
                        @click="onMoveStudent(row.id)"
                        color="white"
                        elevation="0"
                        flat
                      >
                        <v-icon color="warning">mdi-forwardburger</v-icon>
                        <v-tooltip activator="parent" location="bottom">
                          ផ្ទេរសិស្សចេញ
                        </v-tooltip>
                      </v-btn>
                    </div>
                    <div class="ml-10" v-else>បានផ្ទេរ</div>
                  </td>
                </tr>
                <tr></tr>
              </tbody>
            </VTable>
          </v-card-text>
        </VCard>
      </VCol>
    </VRow>
    <v-dialog v-model="dialog_add_student" style="max-width: 1000px" persistent>
      <v-card>
        <v-card-title
          style="display: flex; justify-content: space-between; align-items: center"
          ><span>ជ្រើសរើសសិស្សចូលថ្នាក់</span
          ><v-btn color="primary" variant="text" @click="dialog_add_student = false">
            <VIcon>mdi-close</VIcon>
          </v-btn></v-card-title
        >
        <v-divider></v-divider>
        <v-card-text>
          <v-row>
            <v-col cols="12" lg="9" md="9" sm="9">
              <v-row>
                <v-col cols="6" md="6" lg="6" sm="6">
                  <VTextField
                    v-model="search"
                    placeholder="ស្វែងរក"
                    append-inner-icon="mdi-search"
                    @keypress.enter="q"
                    @click:append-inner="q"
                  />
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="12" lg="3" md="3" sm="3">
              <v-btn
                color="info"
                size="large"
                @click="onConfirmAddStudent()"
                :loading="adding_student"
                ><v-icon>mdi-add</v-icon>&nbsp;ដាក់សិស្សចូលថ្នាក់</v-btn
              ></v-col
            >
            <v-col cols="12" md="12">
              <VTable :headers="headers" :items="data" class="table-rounded">
                <thead>
                  <tr>
                    <th v-for="header in headers" :key="header">
                      {{ header }}
                    </th>
                    <th class="text-right"></th>
                  </tr>
                </thead>

                <tbody>
                  <td v-if="loading" :colspan="headers.length + 1">
                    <v-progress-linear indeterminate class="line"></v-progress-linear>
                  </td>
                  <tr v-if="loading && data.length === 0">
                    <td :colspan="headers.length" class="text-center">
                      <div class="text-subtitle-2">កំពុងដំណើរការ...</div>
                    </td>
                  </tr>
                  <tr v-if="!loading && data.length === 0">
                    <td :colspan="headers.length + 1" class="text-subtitle-2 text-center">
                      មិនមានផ្ទុកទិន្នន័យ...
                    </td>
                  </tr>
                  <tr v-for="row in data" :key="row.id">
                    <td>
                      <v-checkbox
                        :value="row.id"
                        v-model="selected_students"
                      ></v-checkbox>
                    </td>
                    <td v-text="row.code" />
                    <td v-text="row.name" />
                    <td v-text="row.sex_text" />
                    <td v-text="row.dob" />
                  </tr>
                  <tr></tr>
                </tbody>
              </VTable>
            </v-col>
          </v-row>
        </v-card-text>
        <VCard-actions>
          <v-row>
            <v-col cols="12" lg="3" md="12" sm="12" xs="12" class="mt-3 text-center">
              <span v-if="!loading"
                >{{ from }} - {{ to }} {{ total === 0 ? "" : `of ${total}` }}</span
              ></v-col
            >
            <v-col cols="12" lg="9" md="12" sm="12" xs="12">
              <VPagination
                v-model="currentPage"
                class="ml-auto"
                :length="numPages"
                :total-visible="10"
              />
            </v-col>
          </v-row>
        </VCard-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="confirmDialog" style="max-width: 500px" persistent>
      <v-card>
        <v-card-text>
          {{
            action === 2
              ? "តើអ្នកប្រាកដក្នុងការផ្ទេរសិស្សចេញពីថ្នាក់នេះ?"
              : "តើអ្នកប្រាកដក្នុងការដកសិស្សចេញពីថ្នាក់នេះ?"
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
