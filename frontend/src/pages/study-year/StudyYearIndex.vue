<template>
    <div class="q-mx-sm">
        <div class="q-pa-md">
            <div class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize">
                Study Year list
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
                            <q-btn unelevated color="primary" dense class="q-pr-sm" @click="dialogPOPUP">
                                <q-icon name="add"></q-icon>create
                            </q-btn>
                        </div>
                        <q-dialog v-model="dailog" persistent>
                            <q-card class="full-width">
                                <div class="text-start text-center text-capitalize q-pa-xs text-white bg-primary">
                                    create study year
                                </div>
                                <form @submit.prevent="createStudyYears" action="" method="post" class="q-pa-lg q-my-sm">
                                    <div class="row justify-center items-center">
                                        <div class="col-12 q-my-sm q-px-sm">
                                            <q-input outlined dense v-model="form.name" type="text" label="Years"></q-input>
                                        </div>
                                        <div class="col-12"></div>
                                        <div class="q-px-sm q-my-sm col-12 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="text-capitalize text-center bg-blue-5 rounded-borders text-white"
                                                unelevated>
                                                Start date :
                                            </div>
                                            <q-input v-model="form.start" class="q-my-xs" outlined dense
                                                type="date"></q-input>
                                        </div>
                                        <div class="q-px-sm q-my-sm col-12 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="text-capitalize text-center bg-red-5 rounded-borders text-white"
                                                unelevated>
                                                End date :
                                            </div>
                                            <q-input v-model="form.end" class="q-my-xs" outlined dense
                                                type="date"></q-input>
                                        </div>
                                    </div>
                                    <q-card-actions align="right">
                                        <q-btn flat dense label="cancel" color="primary" v-close-popup />
                                        <q-btn unelevated dense type="submit" label="confirm" color="primary"
                                            v-close-popup />
                                    </q-card-actions>
                                </form>
                            </q-card>
                        </q-dialog>
                    </div>
                    <q-table class="shadow-0 bg-blue-1" v-model:pagination="pagination" @request="handleRequest"
                        :rows="rows" :columns="columns">
                        <template v-slot:body-cell-action="props">
                            <q-td :props="props">
                                <div>
                                    <q-btn icon="edit" @click="onEdit(props.row.id)" color="primary" dense flat>
                                        <q-tooltip class="bg-primary">Edit</q-tooltip>
                                    </q-btn>
                                    <q-btn icon="delete" @click="onDelet(props.row.id)" color="red" dense flat>
                                        <q-tooltip class="bg-red">delete</q-tooltip>
                                    </q-btn>
                                </div>
                            </q-td>
                        </template>
                    </q-table>
                    <q-dialog v-model="updateDialog" persistent>
                        <q-card class="q-pb-sm full-width">
                            <div class="text-start text-center q-pa-xs text-white bg-primary">
                                Edit Study Year
                            </div>
                            <form @submit.prevent="updataStudyYears" action="" method="post" class="q-pa-lg q-my-sm">
                                <div class="row justify-center items-center">
                                    <div class="col-12 q-my-sm q-px-sm">
                                        <q-input outlined dense v-model="form.name" type="text" label="Years"></q-input>
                                    </div>
                                    <div class="col-12"></div>
                                    <div class="q-px-sm q-my-sm col-12 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="text-capitalize text-center bg-blue-5 rounded-borders text-white"
                                            unelevated>
                                            Start date :
                                        </div>
                                        <q-input v-model="form.start" class="q-my-xs" outlined dense type="date"></q-input>
                                    </div>
                                    <div class="q-px-sm q-my-sm col-12 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="text-capitalize text-center bg-red-5 rounded-borders text-white"
                                            unelevated>
                                            End date :
                                        </div>
                                        <q-input v-model="form.end" class="q-my-xs" outlined dense type="date"></q-input>
                                    </div>
                                </div>
                                <q-card-actions align="right">
                                    <q-btn flat dense label="cancel" color="primary" v-close-popup />
                                    <q-btn unelevated dense type="submit" label="confirm" color="primary" v-close-popup />
                                </q-card-actions>
                            </form>
                        </q-card>
                    </q-dialog>
                    <q-dialog v-model="deleteDialog" persistent>
                        <q-card>
                            <q-card-section class="row items-center">
                                <q-avatar icon="delete" color="red" text-color="white" />
                                <span class="q-ml-sm">Are you sure to delete this study years ?</span>
                            </q-card-section>

                            <q-card-actions align="right">
                                <q-btn flat dense label="No" color="primary" v-close-popup />
                                <q-btn unelevated dense label="Yes" @click="confirmOnDelete" color="red" />
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
export default {
    data: () => ({
        axiosApiInstance: axiosApiInstance,
        form: {
            name: null,
            start: null,
            end: null,
        },
        dailog: false,
        updateDialog: false,
        deleteDialog: false,
        getID: null,
        rows: [],
        columns: [
            {
                name: "name",
                label: "Years",
                align: "left",
                field: "name",
                sortable: true,
            },
            {
                name: "start",
                label: "Start date",
                align: "left",
                field: "start",
                sortable: true,
            },
            {
                name: "end",
                label: "End date",
                align: "left",
                field: "end",
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
                    `/study_years?per_page=${this.pagination.rowsPerPage === 0
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
        dialogPOPUP() {
            this.dailog = true;
        },
        createStudyYears() {
            this.axiosApiInstance
                .post("/study_years", this.form)
                .then((t) => {
                    console.log(t.data.message);
                    this.fetchStudyYear();
                    this.form = {};
                })
                .catch((f) => {
                    console.log(f);
                });
        },
        onEdit(id) {
            this.getID = id;
            this.updateDialog = true;
            this.axiosApiInstance
                .get("/study_years/" + this.getID)
                .then((t) => {
                    // console.log(t.data.data);
                    this.form = t.data.data;
                })
                .catch((f) => {
                    console.log(f);
                });
        },
        updataStudyYears() {
            this.axiosApiInstance
                .put("/study_years/" + this.getID, this.form)
                .then((t) => {
                    console.log(t.data.message);
                    this.fetchStudyYear();
                })
                .catch((f) => {
                    console.log(f);
                });
        },
        onDelet(id) {
            this.getID = id;
            this.deleteDialog = true;
        },
        confirmOnDelete() {
            this.axiosApiInstance
                .delete("/study_years/" + this.getID)
                .then((t) => {
                    console.log(t.data.message);
                    this.fetchStudyYear();
                })
                .catch((f) => {
                    console.log(f);
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
