<template>
    <div>
        <div class="q-pa-md">
            <div class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize">
                Permission list
            </div>
            <br>
            <q-card>
                <div class="q-pa-md">
                    <div class="q-mb-md">
                        <q-btn :to="{ name: 'home' }" class="q-pr-sm" no-caps outline color="primary" dense>
                            <q-icon name="arrow_left" />
                            Back
                        </q-btn>
                    </div>
                    <div class="q-mt-md">
                        <q-table :loading="loading" class="shadow-0 bg-blue-1" :rows="rows" :columns="columns">
                        </q-table>
                    </div>
                </div>
            </q-card>
        </div>
    </div>
</template>

<script>
import axiosApiInstance from '../../utilites';
export default {
    data: () => ({
        axiosApiInstance: axiosApiInstance,
        loading: true,
        rows: [],
        columns: [
            {
                name: "ID",
                label: "ID",
                align: "left",
                field: "id",
                sortable: true,
            },
            {
                name: "Display_name",
                label: "Display Name",
                align: "left",
                field: "display_name",
                sortable: true,
            },
        ],
    }),
    methods: {
        fetchPermission() {
            this.loading = true;
            this.axiosApiInstance.get('/permissions')
                .then((res) => {
                    this.rows = res.data.data;
                }).catch((err) => {
                    console.log(err);
                }).finally(() => {
                    this.loading = false;
                })
        },
    },
    mounted() {
        this.fetchPermission();
    },
}
</script>

<style scoped></style>