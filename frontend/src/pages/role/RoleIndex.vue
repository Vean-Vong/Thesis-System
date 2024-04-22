<template>
  <div>
    <div>
      <div class="q-mx-sm">
        <div class="q-pa-md">
          <div
            class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
          >
            Role
          </div>
          <br />
          <q-card>
            <div class="q-pa-md">
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
                    :to="{ name: 'role.create' }"
                    color="primary"
                    dense
                    class="q-pr-sm"
                    @click="dialogPOPUP"
                  >
                    <q-icon name="add"></q-icon>create
                  </q-btn>
                </div>
              </div>
              <div class="q-mt-md">
                <q-table
                  class="shadow-0 bg-blue-1"
                  :rows="rows"
                  :loading="loading"
                  :columns="columns"
                  v-model:pagination="pagination"
                  @request="handleRequest"
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
              </div>
              <q-dialog v-model="deleteDialog" persistent>
                <q-card>
                  <q-card-section class="row items-center">
                    <q-avatar icon="delete" color="red" text-color="white" />
                    <span class="q-ml-sm">Are you sure to delete this role ?</span>
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
    </div>
  </div>
</template>

<script>
import axiosApiInstance from "../../utilites";
import store from "../../store";
import router from "../../router";
export default {
  data: () => ({
    getID: null,
    deleteDialog: false,
    axiosApiInstance: axiosApiInstance,
    rows: [],
    columns: [
      {
        name: "Display_name",
        label: "Display Name",
        align: "left",
        field: "display_name",
        sortable: true,
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
    loading: true,
    search: "",
  }),
  watch: {
    search() {
      this.fetchData();
    },
  },
  methods: {
    fetchData() {
      this.loading = true;
      this.axiosApiInstance
        .get(
          `/roles?per_page=${
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
          store.dispatch("pushNotification", {
            message: err.response.data.message,
            type: "error",
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    onEdit(id) {
      router.push(`/role/${id}/edit`);
    },
    onDelet(id) {
      this.getID = id;
      this.deleteDialog = true;
    },
    confirmOnDelete() {
      this.axiosApiInstance
        .delete(`/roles/${this.getID}`)
        .then((res) => {
          store.dispatch("pushNotification", {
            message: res.data.message,
            type: "success",
          });
          console.log(res.data.message);
          this.fetchData();
        })
        .catch((err) => {
          console.log(err);
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
