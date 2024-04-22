<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          Teacher
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
                <!-- @click="onCreate" -->
                <q-btn unelevated color="primary" dense class="q-pr-sm">
                  <q-icon name="add"></q-icon>create
                </q-btn>
              </div>
            </div>
            <q-table
              class="shadow-0 bg-blue-1"
              :loading="loading"
              :rows="rows"
              v-model:pagination="pagination"
              @request="handleRequest"
              :columns="columns"
            >
              <template v-slot:body-cell-no="props">
                <q-td :props="props">
                  {{
                    pagination.rowsPerPage * (pagination.page - 1) + props.pageIndex + 1
                  }}
                </q-td>
              </template>
              <template v-slot:body-cell-action="props">
                <q-td :props="props">
                  <div>
                    <!-- @click="onEdit(props.row.id)" -->
                    <q-btn icon="edit" color="primary" dense flat>
                      <q-tooltip class="bg-primary">Edit</q-tooltip>
                    </q-btn>
                    <!-- @click="onDelete(props.row.id)" -->
                    <q-btn icon="delete" color="red" dense flat>
                      <q-tooltip class="bg-red">delete</q-tooltip>
                    </q-btn>
                  </div>
                </q-td>
              </template>
            </q-table>
          </div>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script>
import axiosApiInstance from "../../utilites";
export default {
  data: () => ({
    axiosApiInstance: axiosApiInstance,
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
        name: "code",
        label: "Code",
        align: "left",
        field: "code",
        sortable: false,
      },
      {
        name: "full_name",
        label: "Name",
        align: "left",
        field: "full_name",
        sortable: false,
      },
      {
        name: "Name Latin",
        label: "Name Latin",
        align: "left",
        field: "full_name_latin",
        sortable: false,
      },
      {
        name: "gender",
        label: "Gender",
        align: "left",
        field: "gender_label",
        sortable: false,
      },
      {
        name: "action",
        label: "Action",
        align: "center",
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
    pagination: {
      sortBy: "id",
      descending: false,
      page: 1,
      rowsPerPage: 15,
      rowsNumber: 0,
    },
    loading: false,
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
          `/teachers?per_page=${
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
          this.$store.dispatch("pushNotification", {
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
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped></style>
