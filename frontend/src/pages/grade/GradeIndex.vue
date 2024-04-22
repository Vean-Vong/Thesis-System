<template>
  <div class="q-mx-sm">
    <div class="q-pa-md">
      <div
        class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
      >
        Grade List
      </div>
      <br />
      <q-card>
        <div class="q-px-md q-pb-md">
          <div class="q-py-md flex justify-between">
            <div>
              <q-input
                outlined
                bottom-slots
                label="search"
                dense
                v-model="search"
                debounce="700"
              >
                <template v-slot:append>
                  <q-icon name="search" />
                </template>
              </q-input>
            </div>
            <div>
              <q-btn unelevated color="primary" dense class="q-pr-sm" @click="onCreate">
                <q-icon name="add"></q-icon>create
              </q-btn>
            </div>
            <q-dialog v-model="formDailog" persistent>
              <q-card class="q-pb-sm full-width">
                <div class="text-start text-center q-pa-xs text-white bg-primary">
                  {{ form.id ? "Update Grade" : "Create Grade" }}
                </div>
                <ValidateForm ref="form_ref" :validation-schema="rules">
                  <form @submit.prevent="onSubmit">
                    <q-card-section
                      class="row block justify-between items-center q-pt-lg full-width"
                    >
                      <div class="q-my-sm">
                        <ValidateField
                          v-slot="{ value, field, errorMessage }"
                          v-model="form.name"
                          name="name"
                        >
                          <q-input
                            dense
                            :model-value="value"
                            v-bind="field"
                            :error="!!errorMessage"
                            :error-message="errorMessage"
                            outlined
                            type="text"
                            label="name"
                          />
                        </ValidateField>
                        <div
                          class="text-bold text-white q-px-sm bg-amber-10 text-capitalize"
                        >
                          Select Subject
                        </div>
                        <div class="row flex justify-center items-center">
                          <div
                            class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-12"
                            v-for="ret in subjects"
                            :key="ret.id"
                          >
                            <q-checkbox
                              v-model="form.subject_ids"
                              :key="ret.id"
                              :val="ret.id"
                              :label="ret.name"
                            />
                          </div>
                        </div>
                        <q-toggle
                          :label="`${form.status == true ? 'Active' : 'Inactive'}`"
                          v-model="form.status"
                        />
                      </div>
                    </q-card-section>
                    <q-card-actions align="right" class="q-pr-md">
                      <q-btn flat color="primary" dense v-close-popup> cancel </q-btn>
                      <q-btn
                        :loading="form.submitting"
                        unelevated
                        type="submit"
                        color="primary"
                        dense
                      >
                        confirm
                      </q-btn>
                    </q-card-actions>
                  </form>
                </ValidateForm>
              </q-card>
            </q-dialog>
          </div>
          <q-table
            class="shadow-0 bg-blue-1"
            :loading="loading"
            :rows="rows"
            :columns="columns"
            v-model:pagination="pagination"
            @request="handleRequest"
          >
            <template v-slot:body-cell-no="props">
              <q-td :props="props">
                {{ pagination.rowsPerPage * (pagination.page - 1) + props.pageIndex + 1 }}
              </q-td>
            </template>
            <template v-slot:body-cell-action="props">
              <q-td :props="props">
                <div>
                  <q-btn
                    icon="edit"
                    @click="onEdit(props.row.id)"
                    color="primary"
                    dense
                    flat
                  >
                    <q-tooltip class="bg-primary">Edit</q-tooltip>
                  </q-btn>
                  <q-btn
                    icon="delete"
                    @click="onDelete(props.row.id)"
                    color="red"
                    dense
                    flat
                  >
                    <q-tooltip class="bg-red">delete</q-tooltip>
                  </q-btn>
                </div>
              </q-td>
            </template>
          </q-table>
          <q-dialog v-model="deleteDialog" persistent>
            <q-card>
              <q-card-section class="row items-center">
                <q-avatar icon="delete" color="red" text-color="white" />
                <span class="q-ml-lg">Are you sure to delete this grade? </span>
              </q-card-section>
              <q-card-actions align="right">
                <q-btn flat dense label="No" color="primary" v-close-popup />
                <q-btn
                  unelevated
                  dense
                  label="Yes"
                  @click="confirmOnDelete"
                  color="red"
                  :loading="loading"
                />
              </q-card-actions>
            </q-card>
          </q-dialog>
        </div>
      </q-card>
    </div>
  </div>
</template>

<script>
import axiosApiInstance from "../../utilites";
import { Form as ValidateForm, Field as ValidateField } from "vee-validate";
import { array, number, object, string } from "yup";
import store from "../../store";
import router from "../../router";
import { mapGetters } from "vuex";
export default {
  components: {
    ValidateField,
    ValidateForm,
  },
  data: () => ({
    axiosApiInstance: axiosApiInstance,
    form: {
      id: null,
      name: "",
      subject_ids: [],
      status: true,
      submitting: false,
    },
    subjects: [],
    deleteDialog: false,
    formDailog: false,
    form_ref: null,
    rows: [],
    loading: true,
    search: "",
    columns: [
      {
        name: "no",
        label: "No",
        align: "left",
        field: "no",
        sortable: false,
      },
      {
        name: "name",
        label: "Name",
        align: "left",
        field: "name",
        sortable: false,
      },
      {
        name: "status",
        label: "Status",
        align: "left",
        field: "status_label",
        sortable: false,
      },
      {
        name: "action",
        label: "Action",
        align: "left",
        field: "action",
        sortable: false,
      },
    ],
    pagination: {
      sortBy: "id",
      descending: false,
      page: 1,
      rowsPerPage: 15,
      rowsNumber: 0,
    },
  }),
  watch: {
    formDailog(newValue) {
      if (newValue === false) {
        this.clearForm();
      }
    },
    search() {
      this.fetchData();
    },
  },
  methods: {
    clearForm() {
      this.form.id = null;
      this.form.name = null;
      this.form.status = true;
      this.form.subject_ids = [];
    },
    onCreate() {
      axiosApiInstance.get("grades/create").then((res) => {
        this.formDailog = true;
        this.subjects = res.data.subjects;
      });
    },
    onEdit(id) {
      this.axiosApiInstance
        .get(`/grades/${id}/edit`)
        .then((res) => {
          this.form.id = id;
          this.form = res.data.data;
          this.subjects = res.data.subjects;
          this.formDailog = true;
        })
        .catch((err) => {
          store.dispatch("pushNotification", {
            message: err.response.data.message,
            type: "error",
          });
        });
    },
    async onSubmit() {
      let { valid } = await this.$refs.form_ref.validate();
      if (valid) {
        this.form.submitting = true;
        if (this.form.id) {
          this.axiosApiInstance
            .put(`/grades/${this.form.id}`, this.form)
            .then((res) => {
              store.dispatch("pushNotification", {
                message: res.data.message,
                type: "success",
              });
              this.fetchData();
              this.formDailog = false;
              this.clearForm();
            })
            .catch((err) => {
              store.dispatch("pushNotification", {
                message: err.response.data.message,
                type: "error",
              });
            })
            .finally(() => {
              this.form.submitting = false;
            });
        } else {
          this.axiosApiInstance
            .post("/grades", this.form)
            .then((res) => {
              store.dispatch("pushNotification", {
                message: res.data.message,
                type: "success",
              });
              this.formDailog = false;
              this.fetchData();
              this.clearForm();
            })
            .catch((err) => {
              store.dispatch("pushNotification", {
                message: err.response.data.message,
                type: "error",
              });
            })
            .finally(() => {
              this.form.submitting = false;
            });
        }
      }
    },
    onDelete(id) {
      this.getID = id;
      this.deleteDialog = true;
    },
    confirmOnDelete() {
      this.axiosApiInstance
        .delete(`/grades/${this.getID}`)
        .then((res) => {
          store.dispatch("pushNotification", {
            message: res.data.message,
            type: "success",
          });
          this.fetchData();
        })
        .catch((err) => {
          store.dispatch("pushNotification", {
            message: err.response.data.message,
            type: "error",
          });
        })
        .finally(() => {
          this.deleteDialog = false;
          this.getID = null;
        });
    },
    fetchData() {
      this.loading = true;
      this.axiosApiInstance
        .get(
          `/grades?per_page=${
            this.pagination.rowsPerPage === 0
              ? this.pagination.rowsNumber
              : this.pagination.rowsPerPage
          }&page=${this.pagination.page}&search=${this.search}`
        )
        .then((res) => {
          this.rows = res.data.data;
          this.pagination.rowsNumber = res.data.meta.total;
        })
        .catch((err) => {
          store.dispatch("pushNotification", {
            message: err.response.data.message,
            type: "error",
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    handleRequest(props) {
      this.pagination.page = props.pagination.page;
      this.pagination.rowsPerPage = props.pagination.rowsPerPage;
      this.fetchData();
    },
  },
  computed: {
    rules() {
      return object({
        name: string().required().label("Name"),
      });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped></style>
