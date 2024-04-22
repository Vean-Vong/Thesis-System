<template>
    <div>
        <div class="q-mx-sm">
            <div class="q-pa-md">
                <div
                    class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize">
                    Class Detail
                </div>
                <br />
                <q-card>
                    <q-card-section>
                        <q-btn label="Back" outline color="primary" no-caps @click="$router.go(-1)"></q-btn>
                    </q-card-section>
                    <q-card-section>
                        <div class="text-center text-h5 text-bold">
                            Class {{ model.name }} Year {{ model?.study_year?.name }}
                        </div>
                        <div class="q-ml-sm">
                            <div class="row q-gutter-lg">
                                <div>Teacher name</div>
                                <div>
                                    :<span class="q-ml-lg">{{ model?.teacher?.full_name }}</span>
                                </div>
                            </div>
                            <div class="row q-gutter-lg">
                                <div>Teacher phone</div>
                                <div>
                                    :<span class="q-ml-lg">{{ model?.teacher?.phone }}</span>
                                </div>
                            </div>
                            <div class="row q-gutter-lg">
                                <div>Total Student</div>
                                <div>
                                    :<span class="q-ml-lg">{{ model?.studies?.length }}</span>
                                </div>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
                <q-card class="q-mt-md">
                    <div class="q-px-md q-pb-md">
                        <div class="q-pt-md flex justify-between">
                            <div>
                                <q-input outlined bottom-slots label="search" v-model="search" dense debounce="700">
                                    <template v-slot:append>
                                        <q-icon name="search" />
                                    </template>
                                </q-input>
                            </div>
                            <div>
                                <q-btn label="Save Attendance" color="primary" no-caps
                                    @click="onCreateAttendance()"></q-btn>
                                <q-btn label="List Attendance" class="q-ml-sm" color="primary" no-caps
                                    @click="onListAttendance()"></q-btn>
                                <q-btn label="Report Attendance" class="q-ml-sm" color="primary" no-caps
                                    @click="onReportAttendance()"></q-btn>
                            </div>
                        </div>
                        <div align="right" class="q-mb-md">
                            <q-btn label="Save Score" class="q-ml-sm" color="primary" no-caps
                                @click="createScore()"></q-btn>
                            <q-btn label="List Score" class="q-ml-sm" color="primary" no-caps
                                @click="onListScore()"></q-btn>
                            <q-btn label="Score Ranking" class="q-ml-sm" color="primary" no-caps
                                @click="scoreRanking()"></q-btn>
                        </div>
                        <div>
                            <div>
                                <div class="row">
                                    <div v-for="item in model.studies" :key="item.id"
                                        class="q-pa-xs col-12 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="bg-card">
                                            <div class="title-card flex text-h2 text-white justify-center items-center">
                                                {{ item.student.last_name }} {{ item.student.first_name }}
                                            </div>
                                            <div class="q-px-md q-my-sm">
                                                <table>
                                                    <tbody class="text-white">
                                                        <tr>
                                                            <td>Code</td>
                                                            <td>:</td>
                                                            <td class="text-lime">{{ item.student.code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Gender</td>
                                                            <td>:</td>
                                                            <td class="text-lime">
                                                                {{ item.student.gender == 1 ? "ប្រុស" : "ស្រី" }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Guadian Phone</td>
                                                            <td>:</td>
                                                            <td class="text-lime">{{ item.student.g_phone_number }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- <div class="flex justify-center q-py-md items-center">
                      </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
        <q-dialog v-model="create_score_dialog" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                    <div class="text-h6">Fill form below</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <div class="q-mb-md">
                        <q-select v-model="form_score.exam_type" map-options emit-value :options="types" option-value="id"
                            option-label="name" outlined label="Type" dense />
                    </div>
                    <div class="q-mb-md">
                        <q-select v-if="form_score.exam_type == 1" v-model="form_score.type" map-options emit-value
                            :options="months" option-value="id" option-label="name" outlined label="Month" dense />
                    </div>
                    <div class="q-mb-md">
                        <q-select v-model="form_score.semester" map-options emit-value :options="semesters"
                            option-value="id" option-label="name" outlined label="Semester" dense />
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                    <q-btn flat label="Cancel" v-close-popup />
                    <q-btn flat label="OK" @click="onCreateScore()" />
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog v-model="score_ranking_dialog" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                    <div class="text-h6">Fill form below</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <div class="q-mb-md">
                        <q-select v-model="form_score.exam_type" map-options emit-value :options="ranking_types"
                            option-value="id" option-label="name" outlined label="Type" dense />
                    </div>
                    <div class="q-mb-md">
                        <q-select v-if="form_score.exam_type == 1" v-model="form_score.type" map-options emit-value
                            :options="months" option-value="id" option-label="name" outlined label="Month" dense />
                    </div>
                    <div class="q-mb-md">
                        <q-select v-if="form_score.exam_type == 0" v-model="form_score.semester" map-options emit-value
                            :options="semesters" option-value="id" option-label="name" outlined label="Semester" dense />
                    </div>
                    <div class="q-mb-md">
                        <q-select v-if="form_score.exam_type == 2" v-model="form_score.score_ranking" map-options emit-value
                            :options="score_rankings" option-value="id" option-label="name" outlined label="Total" dense />
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                    <q-btn flat label="Cancel" v-close-popup />
                    <q-btn flat label="OK" @click="onScoreRanking()" />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
import axiosApiInstance from "../../utilites";
export default {
    data: () => ({
        model: {},
        listing: false,
        search: "",
        create_score_dialog: false,
        score_ranking_dialog: false,
        form_score: {
            type: null,
            exam_type: null,
            semester: null,
            score_ranking: null,
        },
        months: [
            {
                id: 1,
                name: "January",
            },
            {
                id: 2,
                name: "February",
            },
            {
                id: 3,
                name: "March",
            },
            {
                id: 4,
                name: "April",
            },
            {
                id: 5,
                name: "May",
            },
            {
                id: 6,
                name: "June",
            },
            {
                id: 7,
                name: "July",
            },
            {
                id: 8,
                name: "August",
            },
            {
                id: 9,
                name: "September",
            },
            {
                id: 10,
                name: "October",
            },
            {
                id: 11,
                name: "November",
            },
            {
                id: 12,
                name: "December",
            },
        ],
        semesters: [
            {
                id: 1,
                name: "Semester 1",
            },
            {
                id: 2,
                name: "Semester 2",
            },
        ],
        types: [
            {
                id: 0,
                name: "Semester",
            },
            {
                id: 1,
                name: "Monthly",
            },
        ],
        ranking_types: [
            {
                id: 0,
                name: "Semester",
            },
            {
                id: 1,
                name: "Monthly",
            },
            {
                id: 2,
                name: "Total",
            },
        ],
        score_rankings: [
            {
                id: 1,
                name: "Total of semester 1",
            },
            {
                id: 2,
                name: "Total of semester 2",
            },
            {
                id: 3,
                name: "Total of Year",
            },
        ],
    }),
    mounted() {
        this.fetchData();
    },
    watch: {
        search(newValue) {
            this.fetchData();
        },
    },
    methods: {
        fetchData() {
            this.listing = true;
            axiosApiInstance
                .get(`/studyclasses/${this.$route.params.studyclass}?search=${this.search}`)
                .then((res) => {
                    this.model = res.data;
                })
                .finally(() => {
                    this.listing = false;
                });
        },
        onCreateAttendance() {
            this.$router.push({
                name: "attendance.save",
                params: { studyclass: this.$route.params.studyclass },
            });
        },
        onListAttendance() {
            this.$router.push({
                name: "attendance.list",
                params: { studyclass: this.$route.params.studyclass },
            });
        },
        onReportAttendance() {
            this.$router.push({
                name: "attendance.report",
                params: { studyclass: this.$route.params.studyclass },
            });
        },
        createScore() {
            this.create_score_dialog = true;
        },
        onCreateScore() {
            this.$router.push({
                name: "score.save",
                params: { studyclass: this.$route.params.studyclass },
                query: { type: this.form_score.type || 0, semester: this.form_score.semester },
            });
        },
        onListScore() {
            this.$router.push({
                name: "score.list",
                params: { studyclass: this.$route.params.studyclass },
            });
        },
        scoreRanking() {
            this.score_ranking_dialog = true;
        },
        onScoreRanking() {
            this.$router.push({
                name: "score.ranking",
                params: { studyclass: this.$route.params.studyclass },
                query: {
                    type: this.form_score.type || 0,
                    semester: this.form_score.semester,
                    total: this.form_score.score_ranking,
                },
            });
        },
    },
};
</script>

<style scoped>
.bg-card {
    background-color: #2e1065;
    border-radius: 20px;
}

.title-card {
    width: 100%;
    height: 100px;
    display: grid;
    place-content: center;
    border-radius: 10px;
    font-weight: 500;
    text-align: center;
}

.skeleton-bg-card {
    width: 50px;
    opacity: 75%;
}

.sk-header {
    width: 100px;
    opacity: 75%;
}

.Search-input {
    width: 250px;
    opacity: 75%;
}

.Create-btn {
    width: 100px;
    opacity: 75%;
}
</style>
