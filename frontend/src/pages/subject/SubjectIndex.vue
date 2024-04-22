<template>
    <div class="q-mx-sm">
        <div class="q-pa-md">
            <div class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize">
                subject list
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
                            <q-card class="q-pb-sm full-width">
                                <div class="text-start text-center q-pa-xs text-white bg-primary">
                                    {{ form.id ? "Update Subject" : "Create Subject" }}
                                </div>
                                <ValidateForm ref="form_ref" :validation-schema="rules">
                                    <form @submit.prevent="onSubmit">
                                        <q-card-section class="row block justify-between items-center q-pt-lg full-width">
                                            <div class="q-my-sm">
                                                <validate-field v-slot="{ value, field, errorMessage }" v-model="form.name"
                                                    name="name">
                                                    <q-input dense :model-value="value" v-bind="field"
                                                        :error="!!errorMessage" :error-message="errorMessage" outlined
                                                        type="text" label="name" /></validate-field>
                                            </div>
                                            <div class="q-my-sm">
                                                <validate-field v-slot="{ value, field, errorMessage }"
                                                    v-model="form.detail" name="detail">
                                                    <q-input dense :model-value="value" v-bind="field"
                                                        :error="!!errorMessage" :error-message="errorMessage" outlined
                                                        type="text" label="detail" /></validate-field>
                                            </div>
                                        </q-card-section>
                                        <q-card-actions align="right" class="q-pr-md">
                                            <q-btn flat color="primary" dense v-close-popup> cancel </q-btn>
                                            <q-btn :loading="form.submitting" unelevated type="submit" color="primary"
                                                dense>
                                                confirm
                                            </q-btn>
                                        </q-card-actions>
                                    </form>
                                </ValidateForm>
                            </q-card>
                        </q-dialog>
                    </div>
                    <q-table class="shadow-0 bg-blue-1" :rows="rows" v-model:pagination="pagination"
                        @request="handleRequest" :columns="columns" :loading="loading">
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
                    <q-dialog v-model="deleteDialog" persistent>
                        <q-card>
                            <q-card-section class="row items-center">
                                <q-avatar icon="delete" color="red" text-color="white" />
                                <span class="q-ml-lg">Are you sure to delete this subject ? </span>
                            </q-card-section>
                            <q-card-actions align="right">
                                <q-btn flat dense label="No" color="primary" v-close-popup />
                                <q-btn unelevated dense label="Yes" @click="confirmOnDelete" color="red"
                                    :loading="deleting" />
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
export default {
    components: {
        ValidateForm,
        ValidateField,
    },
    data: () => ({
        axiosApiInstance: axiosApiInstance,
        form: {
            name: null,
            detail: null,
            submitting: false,
        },
        deleting: false,
        dailog: false,
        deleteDialog: false,
        form_ref: null,
        getID: null,
        rows: [],
        columns: [
            {
                name: "name",
                label: "Name",
                align: "left",
                field: "name",
                sortable: true,
            },
            {
                name: "detail",
                label: "Detail",
                align: "left",
                field: "detail",
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
    computed: {
        rules() {
            return object({
                name: string().required().label("Name"),
                detail: string().required().label("Detail"),
            });
        },
        ...mapGetters("auth", {
            user: "user",
        }),
    },
    watch: {
        search() {
            this.fetchSubject();
        },
        dailog(newValue) {
            if (newValue === false) {
                this.clearForm();
            }
        },
    },
    methods: {
        clearForm() {
            this.form.id = null;
            this.form.name = null;
            this.form.detail = null;
        },
        fetchSubject() {
            this.loading = true;
            this.axiosApiInstance
                .get(
                    `/subjects?per_page=${this.pagination.rowsPerPage === 0
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
        dialogPOPUP() {
            this.dailog = true;
        },
        onEdit(id) {
            this.axiosApiInstance
                .get("/subjects/" + id)
                .then((res) => {
                    this.getID = id;
                    this.form = res.data.data;
                    this.dailog = true;
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
                        .put("/subjects/" + this.getID, this.form)
                        .then((res) => {
                            store.dispatch("pushNotification", {
                                message: res.data.message,
                                type: "success",
                            });
                            this.dailog = false;
                            this.form = {};
                            this.fetchSubject();
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
                        .post("/subjects", this.form)
                        .then((res) => {
                            store.dispatch("pushNotification", {
                                message: res.data.message,
                                type: "success",
                            });
                            this.dailog = false;
                            this.fetchSubject();
                            this.form = {};
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
        onDelet(id) {
            this.getID = id;
            this.deleteDialog = true;
        },
        confirmOnDelete() {
            this.deleting = true;
            this.axiosApiInstance
                .delete("/subjects/" + this.getID)
                .then((res) => {
                    this.fetchSubject();
                    this.deleteDialog = false;
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
                    this.deleting = false;
                });
        },
        handleRequest(props) {
            this.pagination.page = props.pagination.page;
            this.pagination.rowsPerPage = props.pagination.rowsPerPage;
            this.fetchSubject();
        },
    },
    mounted() {
        this.fetchSubject();
    },
};
</script>

<style scoped></style>
