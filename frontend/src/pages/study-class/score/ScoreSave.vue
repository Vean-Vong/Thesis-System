<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          Save Score
        </div>
        <br />
        <q-card>
          <q-card-section>
            <q-btn
              label="Back"
              outline
              color="primary"
              no-caps
              @click="$router.go(-1)"
            ></q-btn>
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
      </div>
    </div>
    <div class="q-px-lg q-pb-md">
      <q-card>
        <q-card-section>
          <div class="row">
            <div class="text-h6 col-md-7 text-right">
              {{
                $route.query.type !== "0"
                  ? `Monthly Exam of ${month_text}`
                  : "Semester Exam"
              }}
            </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
    <div class="q-px-lg">
      <q-markup-table>
        <thead>
          <tr>
            <th class="text-center">No</th>
            <th class="text-center">Name</th>
            <th class="text-center">Gender</th>
            <th
              class="text-center"
              v-for="subject in subjects"
              :key="subject"
              :style="`width: ${80 / subjects.length}%`"
            >
              {{ subject }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(ret, index) in form.details" :key="index">
            <td class="text-center">{{ index + 1 }}</td>
            <td class="text-left">{{ ret.name }}</td>
            <td class="text-left">{{ ret.gender }}</td>
            <td
              :style="`width: ${80 / subjects.length}%`"
              v-for="item in ret.details"
              :key="item"
            >
              <q-input
                dense
                v-model="item.score"
                class="text-center"
                outlined
                type="text"
              />
            </td>
          </tr>
        </tbody>
      </q-markup-table>
      <div class="q-my-md" align="right">
        <q-btn label="Save" color="green" icon="save" @click="save()" />
      </div>
    </div>
  </div>
</template>

<script>
import axiosApiInstance from "../../../utilites";
export default {
  data: () => ({
    form: {
      details: [],
      type: null,
      semester: 1,
    },
    subjects: [],
    model: {},
    listing: false,
    search: "",
    searching: false,
    month_text: "",
  }),
  mounted() {
    this.form.type = this.$route.query.type;
    this.form.semester = this.$route.query.semester;
    this.fetchData();
    this.onSearch();
  },
  watch: {
    search(newValue) {
      this.fetchData();
    },
    "form.meridiem"(newValue) {
      if (newValue) {
        this.onSearch();
      }
    },
    "form.date"(newValue) {
      if (newValue) {
        this.onSearch();
      }
    },
  },
  computed: {
    rules() {
      return object({
        date: string().required().label("Date"),
        meridiem: string().required().label("Meridiem"),
      });
    },
  },
  watch: {
    "form.semester"(newValue) {
      if (newValue && this.form.type === "0") {
        this.onSearch();
      }
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
    async onSearch() {
      this.searching = true;
      axiosApiInstance
        .post(`study-classes/${this.$route.params.studyclass}/scores/form`, {
          type: this.$route.query.type,
          semester: this.form.semester,
        })
        .then((res) => {
          this.form.details = res.data.data;
          this.subjects = res.data.subjects;
          this.form.semester = res.data.semester;
          this.month_text = res.data.month_text;
        })
        .finally(() => {
          this.searching = false;
        });
    },
    save() {
      axiosApiInstance
        .post(`study-classes/${this.$route.params.studyclass}/scores/save`, this.form)
        .then((res) => {
          console.log(res);
          this.$router.push({
            name: "studyclass.show",
            params: { studyclass: this.$route.params.studyclass },
          });
        })
        .finally(() => {
          console.log("done");
        });
    },
  },
};
</script>
