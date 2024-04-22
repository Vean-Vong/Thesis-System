<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          Class Edit
        </div>
        <br />
        <q-card>
          <div class="q-px-md q-pb-md">
            <div class="q-py-md flex justify-between">
              <div class="q-px-sm q-pt-sm">
                <q-btn
                  outline
                  color="primary"
                  no-caps
                  dense
                  class="q-pr-sm"
                  :to="{ name: 'studyclass.index' }"
                >
                  <q-icon name="arrow_left"></q-icon>Back
                </q-btn>
              </div>
            </div>
            <div class="rounded-borders">
              <form @submit.prevent="onSubmit" class="text-capitalize">
                <div class="row items-center justify-center">
                  <div
                    class="q-pa-sm col-12 col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12"
                  >
                    <q-input v-model="form.name" outlined dense label="Name"></q-input>
                  </div>
                  <div
                    class="q-pa-sm col-12 col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12"
                  >
                    <!-- v-model="model" :options="options" -->
                    <q-select
                      v-model="form.teacher_id"
                      map-options
                      emit-value
                      :options="teachers"
                      option-value="id"
                      option-label="full_name"
                      outlined
                      label="Teacher"
                      dense
                    />
                  </div>
                  <div
                    class="q-pa-sm col-12 col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12"
                  >
                    <!-- v-model="model" :options="options" -->
                    <q-toggle
                      :label="`${form.status == true ? 'Active' : 'Inactive'}`"
                      v-model="form.status"
                    />
                  </div>
                </div>
                <div class="row items-center justify-center">
                  <div
                    class="q-pa-sm col-12 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12"
                  >
                    <q-select
                      v-model="form.study_year_id"
                      :options="study_years"
                      map-options
                      emit-value
                      option-label="name"
                      outlined
                      label="Study Year"
                      option-value="id"
                      dense
                    />
                  </div>
                  <div
                    class="q-pa-sm col-12 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12"
                  >
                    <q-select
                      v-model="form.grade_id"
                      :options="grades"
                      emit-value
                      map-options
                      option-value="id"
                      option-label="name"
                      outlined
                      label="Grade"
                      dense
                    />
                  </div>
                  <div
                    class="q-pa-sm col-12 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12"
                  >
                    <q-select
                      v-model="form.room_id"
                      :options="rooms"
                      map-options
                      emit-value
                      option-label="name"
                      option-value="id"
                      outlined
                      label="Room"
                      dense
                    />
                  </div>
                  <div
                    class="q-pa-sm col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"
                  >
                    <div
                      class="text-bold text-grey-1 q-pl-sm bg-blue-grey-3 rounded-borders"
                    >
                      Subjects
                    </div>
                  </div>

                  <div
                    v-for="subject in subjects"
                    :key="subject.id"
                    class="q-pa-sm col-12 col-lg-4 col-md-4 col-sm-4"
                  >
                    <q-checkbox
                      v-model="form.subject_ids"
                      :key="subject.id"
                      :val="subject.id"
                      :label="subject.name"
                    />
                  </div>

                  <div
                    class="q-pa-sm col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"
                  >
                    <div class="flex items-center justify-start">
                      <q-btn
                        dense
                        class="q-px-sm"
                        unelevated
                        @click="onAddStudent()"
                        color="primary"
                        v-if="form.study_year_id"
                        no-caps
                        label="Add Student"
                      ></q-btn>
                    </div>
                  </div>

                  <div
                    class="q-pa-sm col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"
                  >
                    <div class="flex items-center justify-start row q-gutter-x-lg">
                      <q-card
                        class="col-6 col-md-4 col-lg-3"
                        v-for="item in selected_students"
                        :key="item.id"
                      >
                        <q-card-section class="text-subtitle1">
                          <div class="text-bold">
                            <span
                              ><q-btn
                                color="red"
                                dense
                                @click="removeStudent(item.id)"
                                icon="remove"
                              ></q-btn
                            ></span>
                            <div class="text-center">{{ item.name }}</div>
                          </div>
                          <div class="text-center">
                            {{ item.sex }}
                          </div>
                        </q-card-section>
                      </q-card>
                    </div>
                    <div
                      class="q-pa-sm col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"
                    >
                      <div class="flex items-center justify-end">
                        <q-btn
                          dense
                          class="q-px-sm"
                          unelevated
                          type="submit"
                          color="primary"
                          no-caps
                          label="UPDATE"
                        ></q-btn>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </q-card>
      </div>
    </div>

    <q-dialog
      v-model="dialog_add_student"
      persistent
      :maximized="true"
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card class="bg-white">
        <q-bar>
          <q-space />
          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>

        <!-- <q-card-section>

        </q-card-section> -->
        <q-card>
          <div class="q-px-md q-pb-md">
            <div class="q-py-md flex justify-between">
              <div>
                <div class="text-h6">Search Student</div>
              </div>
              <div>
                <q-input
                  outlined
                  bottom-slots
                  label="search"
                  v-model="search"
                  debounce="700"
                  dense
                >
                  <template v-slot:append>
                    <q-icon name="search" />
                  </template>
                </q-input>
              </div>
            </div>
            <q-table
              class="shadow-0 bg-blue-1"
              :loading="loading"
              :rows="rows"
              :columns="columns"
              v-model:pagination="pagination"
              @request="handleRequest"
            >
              <template v-slot:body-cell-Status="props">
                <q-td :props="props">
                  <span
                    class="action-status-active"
                    v-if="props.row.status_label == 'Active'"
                  >
                    {{ props.row.status_label }}
                  </span>

                  <span class="action-status-inactive" v-else>
                    {{ props.row.status_label }}
                  </span>
                </q-td>
              </template>
              <template v-slot:body-cell-action="props">
                <q-td :props="props">
                  <q-btn
                    class="q-px-sm"
                    icon="add"
                    @click="onFindStudent(props.row.id)"
                    color="primary"
                    dense
                    flat
                  >
                    <q-tooltip class="bg-blue">add student</q-tooltip>
                  </q-btn>
                </q-td>
              </template>
            </q-table>
          </div>
        </q-card>
        <q-card-section class="q-pt-none"> </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>
<script>
import axiosApiInstance from "../../utilites";
export default {
  data: () => ({
    axiosApiInstance: axiosApiInstance,
    form: {
      name: null,
      teacher_id: null,
      study_year_id: null,
      grade_id: null,
      room_id: null,
      subject_ids: [],
      student_ids: [],
      status: true,
    },
    teachers: [],
    subjects: [],
    grades: [],
    study_years: [],
    rooms: [],
    selected_students: [],
    pagination: {
      sortBy: "id",
      descending: false,
      page: 1,
      rowsPerPage: 15,
      rowsNumber: 0,
    },
    dialog_add_student: false,
    columns: [
      {
        name: "Code",
        label: "Code",
        align: "left",
        field: "code",
        sortable: false,
      },
      {
        name: "Fullname",
        label: "Full name",
        align: "left",
        field: "full_name",
        sortable: true,
      },
      {
        name: "Status",
        label: "Status",
        align: "left",
        field: "status_label",
        sortable: false,
      },
      {
        name: "Gender",
        label: "Gender",
        align: "left",
        field: "gender_label",
        sortable: false,
      },
      {
        name: "Day of Birth",
        label: "Day of Birth",
        align: "left",
        field: "dob",
        sortable: true,
      },
      {
        name: "Phone",
        label: "Phone",
        align: "left",
        field: "phone",
        sortable: true,
      },
      {
        name: "action",
        label: "Actions",
        align: "center",
        field: "Action",
        sortable: false,
      },
    ],
    rows: [],
    search: "",
  }),
  mounted() {
    this.fetchData();
  },
  watch: {
    search() {
      this.fetchStudent();
    },
  },
  methods: {
    onSubmit() {
      this.axiosApiInstance
        .put(`/studyclasses/${this.$route.params.studyclass}`, this.form)
        .then((res) => {
          this.$store.dispatch("pushNotification", {
            type: "success",
            message: res.data.message,
          });
          this.$router.push({ name: "studyclass.index" });
        })
        .catch((err) => {
          this.$store.dispatch("pushNotification", {
            type: "error",
            message: err.response.data.message,
          });
        });
    },
    fetchData() {
      this.axiosApiInstance
        .get(`/studyclasses/${this.$route.params.studyclass}/edit`)
        .then((res) => {
          this.form = res.data.data.data;
          this.grades = res.data.data.grades;
          this.study_years = res.data.data.study_years;
          this.teachers = res.data.data.teachers;
          this.rooms = res.data.data.rooms;
          this.subjects = res.data.data.subjects;
          if (this.form.status == 1) {
            this.form.status = true;
          } else {
            this.form.status = false;
          }
          res.data.data.data.studies.forEach((e) => {
            this.selected_students.push({
              id: e.student.id,
              name: `${e.student.last_name} ${e.student.first_name}`,
              sex: `${e.student.gender === 1 ? "ប្រុស" : "ស្រី"}`,
            });
          });
        })
        .catch((err) => {
          this.$store.dispatch("pushNotification", {
            type: "error",
            message: err.response.data.message,
          });
        });
    },
    onAddStudent() {
      this.dialog_add_student = true;
      this.fetchStudent();
    },
    fetchStudent() {
      this.loading = true;
      this.axiosApiInstance
        .post(
          `/studyclasses-filter-student?per_page=${
            this.pagination.rowsPerPage === 0
              ? this.pagination.rowsNumber
              : this.pagination.rowsPerPage
          }&page=${this.pagination.page}&search=${this.search}`,
          {
            study_year_id: this.form.study_year_id,
            student_ids: this.form.student_ids,
          }
        )
        .then((res) => {
          this.rows = res.data.data;
          this.pagination.rowsNumber = res.data.meta.total;
        })
        .catch((err) => {
          this.$store.dispatch("pushNotification", {
            type: "error",
            message: err.response.data.message,
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    onFindStudent(id) {
      axiosApiInstance
        .post("/studyclasses-find-student", {
          student_id: id,
        })
        .then((res) => {
          this.form.student_ids.push(res.data.id);
          this.selected_students.push({
            id: res.data.id,
            name: `${res.data.last_name} ${res.data.first_name}`,
            sex: `${res.data.gender === 1 ? "ប្រុស" : "ស្រី"}`,
          });
          this.dialog_add_student = false;
        });
    },
    handleRequest(props) {
      this.pagination.page = props.pagination.page;
      this.pagination.rowsPerPage = props.pagination.rowsPerPage;
      this.fetchStudent();
    },
    removeStudent(id) {
      this.form.student_ids = this.form.student_ids.filter((e) => e !== id);
      this.selected_students = this.selected_students.filter((e) => e.id !== id);
    },
    clearForm() {
      this.form.name = null;
      this.form.teacher_id = null;
      this.form.study_year_id = null;
      this.form.grade_id = null;
      this.form.subject_ids = [];
      this.form.status = null;
    },
  },
};
</script>

<style scoped></style>
