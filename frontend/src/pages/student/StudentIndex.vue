<template>
    <div class="q-mx-sm">
        <div class="q-pa-md">
            <div class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize">
                student list
            </div>
            <br />
            <q-card>
                <div class="q-px-md q-pb-md">
                    <div class="q-py-md flex justify-between">
                        <div>
                            <q-input outlined bottom-slots label="search" dense v-model="search" debounce="700">
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                            </q-input>
                        </div>
                        <div>
                            <q-btn unelevated color="primary" dense class="q-pr-sm" :to="{ name: 'student.create' }">
                                <q-icon name="add"></q-icon>create
                            </q-btn>
                        </div>
                    </div>
                    <q-table class="shadow-0 bg-blue-1" :loading="loading" :rows="rows" v-model:pagination="pagination"
                        @request="handleRequest" :columns="columns">
                        <template v-slot:body-cell-Status="props">
                            <q-td :props="props">
                                <span class="action-status-active" v-if="props.row.status_label == 'Active'">
                                    {{ props.row.status_label }}
                                </span>

                                <span class="action-status-inactive" v-else>
                                    {{ props.row.status_label }}
                                </span>
                            </q-td>
                        </template>
                        <template v-slot:body-cell-action="props">
                            <q-td :props="props">
                                <q-btn class="q-px-sm" icon="remove_red_eye" @click="onEdit(props.row.id)" color="primary"
                                    dense flat>
                                    <q-tooltip class="bg-primary">Detail</q-tooltip>
                                </q-btn>

                                <q-btn class="q-px-sm" icon="edit" @click="
                                    $router.push({
                                        name: 'student.edit',
                                        params: { student: props.row.id },
                                    })
                                    " color="primary" dense flat>
                                    <q-tooltip class="bg-primary">Edit</q-tooltip>
                                </q-btn>

                                <q-btn class="q-px-sm" icon="delete" @click="onDelete(props.row.id)" color="red" dense flat>
                                    <q-tooltip class="bg-red">delete</q-tooltip>
                                </q-btn>
                            </q-td>
                        </template>
                    </q-table>
                    <q-dialog persistent>
                        <q-card>
                            <q-card-section class="row items-center">
                                <q-avatar icon="delete" color="red" text-color="white" />
                                <span class="q-ml-sm">Are you sure to delete this user ?</span>
                            </q-card-section>

                            <q-card-actions align="right">
                                <q-btn flat dense label="No" color="primary" v-close-popup />
                                <q-btn unelevated dense label="Yes" color="red" v-close-popup />
                            </q-card-actions>
                        </q-card>
                    </q-dialog>
                </div>
            </q-card>
        </div>
        <q-dialog v-model="deleteDialog" persistent>
            <q-card>
                <q-card-section class="row items-center">
                    <q-avatar icon="delete" color="red" text-color="white" />
                    <span class="q-ml-sm">Are you sure to delete this student?</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat dense label="No" color="primary" v-close-popup />
                    <q-btn unelevated dense label="Yes" @click="confirmOnDelete" color="red" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>
<script>
import axiosApiInstance from "../../utilites";
import router from "../../router";
export default {
    data: () => ({
        axiosApiInstance: axiosApiInstance,
        rows: [],
        loading: true,
        deleteDialog: false,
        delete_item: null,
        deleting: false,
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
                    `/students?per_page=${this.pagination.rowsPerPage === 0
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
        onEdit(id) {
            router.push(`/student/${id}/show`);
        },
        onDelete(id) {
            this.delete_item = id;
            this.deleteDialog = true;
        },
        confirmOnDelete() {
            this.deleting = true;
            this.axiosApiInstance
                .delete(`students/${this.delete_item}`)
                .then((t) => {
                    this.$store.dispatch("pushNotification", {
                        type: "success",
                        message: t.data.message,
                    });
                    this.fetchStudentData();
                })
                .catch((f) => {
                    this.$store.dispatch("pushNotification", {
                        type: "error",
                        message: f.response.data.message,
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
<style scoped>
.action-status-active {
    background-color: #22c55e;
    color: white;
    font-weight: bold;
    justify-items: center;
    align-items: center;
    padding: 2px 6px 4px 6px;
    letter-spacing: 1px;
    border-radius: 10px;
}

.action-status-inactive {
    background-color: #ef4444;
    color: white;
    font-weight: bold;
    justify-items: center;
    align-items: center;
    padding: 2px 6px 4px 6px;
    letter-spacing: 1px;
    border-radius: 10px;
}
</style>
