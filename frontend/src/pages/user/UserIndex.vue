<template>
  <div class="q-mx-sm">
    <div class="q-pa-md">
      <div
        class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize"
      >
        user list
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
              <q-btn
                unelevated
                color="primary"
                dense
                class="q-pr-sm"
                @click="dialogPOPUP"
              >
                <q-icon name="add"></q-icon>create
              </q-btn>
            </div>
            <q-dialog v-model="dailog" persistent>
              <q-card class="q-pb-sm full-width">
                <div
                  class="text-start text-center text-capitalize q-pa-xs text-white bg-primary"
                >
                  create user
                </div>
                <form @submit.prevent="createUser" action="" method="post">
                  <q-card-section
                    class="row block justify-between items-center q-pt-lg full-width"
                  >
                    <div class="q-my-sm">
                      <q-input
                        outlined
                        type="text"
                        dense
                        label="username"
                        class="input"
                        v-model="form.name"
                      ></q-input>
                    </div>
                    <div class="q-my-sm">
                      <q-input
                        outlined
                        type="email"
                        dense
                        label="email"
                        class="input"
                        v-model="form.email"
                      ></q-input>
                    </div>
                    <div class="q-my-sm">
                      <q-select
                        outlined
                        class="text-capitalize"
                        dense
                        :options="options"
                        option-label="display_name"
                        option-value="id"
                        label="Select Role user"
                        v-model="form.roles"
                        emit-value
                        map-options
                        multiple
                      />
                    </div>
                  </q-card-section>
                  <q-card-actions align="right" class="q-pr-md">
                    <q-btn flat color="primary" dense v-close-popup> cancel </q-btn>
                    <q-btn unelevated type="submit" color="primary" v-close-popup dense>
                      confirm
                    </q-btn>
                  </q-card-actions>
                </form>
              </q-card>
            </q-dialog>
          </div>
          <q-table
            class="shadow-0 bg-blue-1"
            v-model:pagination="pagination"
            @request="handleRequest"
            :rows="rows"
            :loading="loading"
            :columns="columns"
          >
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
                    @click="onDelet(props.row.id)"
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
          <q-dialog v-model="updateDialog" persistent>
            <q-card class="q-pb-sm full-width">
              <div class="text-start text-center q-pa-xs text-white bg-primary">
                Edit user
              </div>
              <form @submit.prevent="updateUser" action="" method="post">
                <q-card-section
                  class="row block justify-between items-center q-pt-lg full-width"
                >
                  <div class="q-my-sm">
                    <q-input
                      outlined
                      type="text"
                      dense
                      label="name"
                      class="input"
                      v-model="form.name"
                    ></q-input>
                  </div>
                  <div class="q-my-sm">
                    <q-input
                      outlined
                      type="text"
                      dense
                      label="email"
                      class="input"
                      v-model="form.email"
                    ></q-input>
                  </div>
                  <div class="q-my-sm">
                    <q-select
                      outlined
                      class="text-capitalize"
                      dense
                      :options="options"
                      option-label="display_name"
                      option-value="id"
                      label="Select Role user"
                      v-model="form.roles"
                      emit-value
                      map-options
                      multiple
                    />
                  </div>
                </q-card-section>
                <q-card-actions align="right" class="q-pr-md">
                  <q-btn flat color="primary" dense v-close-popup> cancel </q-btn>
                  <q-btn unelevated type="submit" color="primary" v-close-popup dense>
                    update
                  </q-btn>
                </q-card-actions>
              </form>
            </q-card>
          </q-dialog>
          <q-dialog v-model="deleteDialog" persistent>
            <q-card>
              <q-card-section class="row items-center">
                <q-avatar icon="delete" color="red" text-color="white" />
                <span class="q-ml-sm">Are you sure to delete this user ?</span>
              </q-card-section>

              <q-card-actions align="right">
                <q-btn flat dense label="No" color="primary" v-close-popup />
                <q-btn
                  unelevated
                  dense
                  label="Yes"
                  @click="confirmOnDelete"
                  color="red"
                  v-close-popup
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
import store from "../../store";
import axiosApiInstance from "../../utilites";
export default {
  data: () => ({
    axiosApiInstance: axiosApiInstance,
    options: [],
    form: {
      name: null,
      email: null,
      roles: [],
    },
    dailog: false,
    search: "",
    updateDialog: false,
    deleteDialog: false,
    getID: null,
    rows: [],
    columns: [
      {
        name: "name",
        label: "Username",
        align: "left",
        field: "name",
        sortable: true,
      },
      {
        name: "email",
        label: "email",
        align: "left",
        field: "email",
        sortable: true,
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
  }),
  watch: {
    search(newValue) {
      this.fetchUser();
    },
  },
  methods: {
    fetchUser() {
      this.loading = true;
      this.axiosApiInstance
        .get(
          `/users?per_page=${
            this.pagination.rowsPerPage === 0
              ? this.pagination.rowsNumber
              : this.pagination.rowsPerPage
          }&page=${this.pagination.page}&search=${this.search}`
        )
        .then((res) => {
          this.rows = res.data.data.data;
          this.pagination.rowsNumber = res.data.data.total;
        })
        .catch((err) => {
          console.log(err);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    dialogPOPUP() {
      this.dailog = true;
      this.form = {};
    },
    createUser() {
      this.axiosApiInstance
        .post("/users", this.form)
        .then((res) => {
          store.dispatch("pushNotification", {
            message: res.data.message,
            type: "success",
          });
          this.fetchUser();
          this.form = {};
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onEdit(id) {
      this.getID = id;
      this.updateDialog = true;
      this.axiosApiInstance
        .get(`/users/${this.getID}/edit`)
        .then((res) => {
          this.form = res.data.data.form;
          // this.form.password = null;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    updateUser() {
      this.axiosApiInstance
        .put(`/users/${this.getID}`, this.form)
        .then((res) => {
          store.dispatch("pushNotification", {
            message: res.data.message,
            type: "success",
          });
          this.fetchUser();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onDelet(id) {
      this.getID = id;
      this.deleteDialog = true;
    },
    confirmOnDelete() {
      this.axiosApiInstance
        .delete(`/users/${this.getID}`)
        .then((res) => {
          store.dispatch("pushNotification", {
            message: res.data.message,
            type: "success",
          });
          console.log(res.data.message);
          this.fetchUser();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    fetchRole() {
      this.axiosApiInstance
        .get("/users/create")
        .then((res) => {
          // console.log(res.data.roles);
          this.options = res.data.roles;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    handleRequest(props) {
      this.pagination.page = props.pagination.page;
      this.pagination.rowsPerPage = props.pagination.rowsPerPage;
      this.fetchUser();
    },
  },
  mounted() {
    this.fetchUser();
    this.fetchRole();
  },
};
</script>

<style scoped></style>
