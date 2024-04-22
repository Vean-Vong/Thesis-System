<template>
  <div class="q-mx-sm">
    <div class="q-pa-md">
      <div
        class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize"
      >
        Room list
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
            <q-dialog v-model="dialog_form" persistent>
              <q-card class="q-pb-sm full-width">
                <div class="text-start text-center q-pa-xs text-white bg-primary">
                  {{ form.id ? "Update Room" : "Create Room" }}
                </div>
                <ValidateForm ref="form_ref" :validation-schema="rules">
                  <form @submit.prevent="onSubmit">
                    <q-card-section
                      class="row block justify-between items-center q-pt-lg full-width"
                    >
                      <div class="q-my-sm">
                        <validate-field
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
                        /></validate-field>
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
            :rows="rows"
            v-model:pagination="pagination"
            @request="handleRequest"
            :columns="columns"
            :loading="loading"
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
                <span class="q-ml-lg">Are you sure to delete this room? </span>
              </q-card-section>
              <q-card-actions align="right">
                <q-btn flat dense label="No" color="primary" v-close-popup />
                <q-btn
                  unelevated
                  dense
                  label="Yes"
                  @click="confirmOnDelete"
                  color="red"
                  :loading="deleting"
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
import { mapGetters } from "vuex";
import axiosApiInstance from "../../utilites";
import { Form as ValidateForm, Field as ValidateField } from "vee-validate";
import { array, number, object, string } from "yup";
import store from "../../store";
import router from "../../router";
export default {
  components: {
    ValidateForm,
    ValidateField,
  },
  data: () => ({
    axiosApiInstance: axiosApiInstance,
    form: {
      id: null,
      name: null,
      status: true,
      submitting: false,
    },
    deleting: false,
    delete_item: null,
    deleteDialog: false,
    dialog_form: false,
    form_ref: null,
    getID: null,
    rows: [],
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
        sortable: true,
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
        label: "Handle",
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
    loading: true,
    search: "",
  }),
  computed: {
    rules() {
      return object({
        name: string().required().label("Name"),
      });
    },
    ...mapGetters("auth", {
      user: "user",
    }),
  },
  watch: {
    search() {
      this.fetchData();
    },
    dialog_form(newValue) {
      if (newValue === false) {
        this.clearForm();
      }
    },
  },
  methods: {
    clearForm() {
      this.form.id = null;
      this.form.name = null;
      this.form.status = true;
    },
    fetchData() {
      this.loading = true;
      this.axiosApiInstance
        .get(
          `/rooms?per_page=${
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
    onCreate() {
      this.dialog_form = true;
    },
    onEdit(id) {
      this.axiosApiInstance
        .get("/rooms/" + id + "/edit")
        .then((t) => {
          this.form.id = id;
          this.form = t.data.data;
          this.dialog_form = true;
        })
        .catch((f) => {
          store.dispatch("pushNotification", {
            message: f.response.data.message,
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
            .put("/rooms/" + this.form.id, this.form)
            .then((res) => {
              store.dispatch("pushNotification", {
                message: res.data.message,
                type: "success",
              });
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
            .post("/rooms", this.form)
            .then((res) => {
              store.dispatch("pushNotification", {
                message: res.data.message,
                type: "success",
              });
              this.dialog_form = false;
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
      this.deleting = true;
      this.axiosApiInstance
        .delete("/rooms/" + this.getID)
        .then((res) => {
          this.fetchData();
          store.dispatch("pushNotification", {
            message: res.data.message,
            type: "success",
          });
          this.deleteDialog = false;
        })
        .catch((err) => {
          store.dispatch("pushNotification", {
            message: err.response.data.message,
            type: "error",
          });
        })
        .finally(() => {
          this.deleting = false;
        });
    },
    handleRequest(props) {
      this.pagination.page = props.pagination.page;
      this.pagination.rowsPerPage = props.pagination.rowsPerPage;
      this.fetchData();
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped></style>
