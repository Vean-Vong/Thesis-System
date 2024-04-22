<template>
    <div>
        <div class="q-mx-sm">
            <div class="q-pa-md">
                <div
                    class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize">
                    Edit Permission
                </div>
                <br>
                <q-card>
                    <div class="q-px-md q-py-md">
                        <div class="q-mb-md">
                            <q-btn :to="{ name: 'role.index' }" class="q-pr-sm" no-caps outline color="primary" dense>
                                <q-icon name="arrow_left" />
                                Back
                            </q-btn>
                        </div>
                        <div class="row flex justify-center">
                            <div class="col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="q-pr-lg q-pt-sm q-pb-sm">
                                    <div>
                                        <form action="">
                                            <div class="q-mb-sm">
                                                <q-input label="Name" outlined dense v-model="form.name"></q-input>
                                            </div>
                                            <div class="q-mt-sm">
                                                <q-input label="Display Name" class="text-capitalize"
                                                    v-model="form.display_name" outlined dense></q-input>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="q-pt-sm q-pb-sm">
                                    <div>
                                        <q-card v-if="loading">
                                            <div class="text-bold text-white q-px-sm bg-amber-10 text-capitalize">
                                                select permission
                                            </div>
                                            <div class="row q-pa-md flex justify-center">
                                                <div class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-12"
                                                    v-for="permission in permissionOpt" :key="permission.id">
                                                    <q-checkbox v-model="form.permission_ids" :key="permission.id"
                                                        :val="permission.id" :label="permission.display_name" />
                                                </div>
                                            </div>
                                        </q-card>
                                        <div class="q-px-sm" v-else>
                                            <q-item-section>
                                                <q-item-label>
                                                    <q-skeleton type="text" />
                                                </q-item-label>
                                                <br>
                                                <q-item-label>
                                                    <q-skeleton type="text" />
                                                </q-item-label>
                                                <br>
                                                <q-item-label>
                                                    <q-skeleton type="text" />
                                                </q-item-label>
                                            </q-item-section>
                                        </div>
                                    </div>
                                    <div class="col-12 flex justify-end q-mt-md">
                                        <q-btn @click="onSubmit" unelevated color="primary" type="submit"
                                            label="update"></q-btn>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
    </div>
</template>

<script>
import router from '../../router';
import store from '../../store';
import axiosApiInstance from '../../utilites';
export default {
    data: () => ({
        loading: false,
        axiosApiInstance: axiosApiInstance,
        form: {
            permission_ids: [],
            name: '',
            display_name: '',
        },
        permissionOpt: [],

    }),
    methods: {
        fetchData() {
            this.axiosApiInstance.get(`/roles/${this.$route.params.role}/edit`)
                .then((res) => {
                    // console.log(res.data.data.form);
                    this.form = res.data.data.form;
                    this.permissionOpt = res.data.data.permissionOpt;
                }).catch((err) => {
                    console.log(err);
                }).finally(() => {
                    setTimeout(() => {
                        this.loading = true;
                    })
                })

        },
        onSubmit() {
            this.axiosApiInstance.put(`/roles/${this.$route.params.role}`, this.form)
                .then((res) => {
                    // console.log(res.data.message);
                    store.dispatch('pushNotification', { message: res.data.message, type: 'success' });
                    router.push({ name: 'role.index' })
                }).catch((err) => {
                    console.log(err);
                });
        }
    },
    mounted() {
        this.fetchData();
    },
}
</script>

<style scoped></style>