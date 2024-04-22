<template>
    <div>
        <div class="q-mx-sm q-pa-md">
            <div class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize">
                Dashboard
            </div>
            <br />
            <q-card>
                <div class="q-px-md q-py-lg">
                    <div class="row justify-center">
                        <div class="rounded-borders col-12 col-lg-6 q-pa-sm col-md-6 col-sm-12 text-white">
                            <div style="background-color: #2ea7c3;" class=" rounded-borders q-pl-lg q-py-md">
                                <div class="flex justify-start items-center">
                                    <div class="flex items-center   ">
                                        <div class="q-py-md">
                                            <q-icon name="groups" size="5rem"></q-icon>
                                        </div>
                                        <div class="q-px-lg">
                                            <div class="text-h5">Total Students</div>
                                            <div class="text-h5">{{ total_student }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-borders col-12 col-lg-6 q-pa-sm col-md-6 col-sm-12 text-white">
                            <div style="background-color: #f54879;" class="rounded-borders q-pl-lg q-py-md">
                                <div class="flex justify-start items-center">
                                    <div class="flex items-center   ">
                                        <div class="q-py-md">
                                            <q-icon name="groups_3" size="5rem"></q-icon>
                                        </div>
                                        <div class="q-px-lg">
                                            <div class="text-h5">Teacher</div>
                                            <div class="text-h5">{{ total_teacher }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-borders col-12 col-lg-6 q-pa-sm col-md-6 col-sm-12 text-white">
                            <div style="background-color: #fabc1c;" class="rounded-borders q-pl-lg q-py-md">
                                <div class="flex justify-start items-center">
                                    <div class="flex items-center   ">
                                        <div class="q-py-md">
                                            <q-icon name="dataset" size="5rem"></q-icon>
                                        </div>
                                        <div class="q-px-lg">
                                            <div class="text-h5">Class</div>
                                            <div class="text-h5">{{ total_class }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-borders col-12 col-lg-6 q-pa-sm col-md-6 col-sm-12 text-white">
                            <div style="background-color: #32c1a3;" class="rounded-borders q-pl-lg q-py-md">
                                <div class="flex justify-start items-center">
                                    <div class="flex items-center   ">
                                        <div class="q-py-md">
                                            <q-icon name="account_circle" size="5rem"></q-icon>
                                        </div>
                                        <div class="q-px-lg">
                                            <div class="text-h5">User</div>
                                            <div class="text-h5">{{ total_user }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        total_teacher: '',
        total_student: '',
        total_user: '',
        total_class: '',
    }),
    methods: {
        fetch() {
            this.axiosApiInstance.get('/dashboard').then((res) => {
                console.log(res.data);
                this.total_student = res.data.total_students;
                this.total_teacher = res.data.total_teachers;
                this.total_user = res.data.total_users;
                this.total_class = res.data.study_class;
            }).catch((err) => {
                console.log(err);
            });
        }
    },
    mounted() {
        this.fetch();
    }
};
</script>
<style scoped></style>
