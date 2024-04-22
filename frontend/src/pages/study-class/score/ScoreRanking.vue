<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          Score Ranking
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
            <div align="right" v-if="is_searched">
              <q-btn label="Print" color="grey" @click="print" />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <div class="q-px-lg" v-if="is_searched">
      <!-- <q-card class="q-pa-lg"> -->
      <div id="table">
        <div
          style="
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 20x;
          "
        >
          <span v-if="$route.query.total === '3'">Total of year</span>
          <span v-else-if="$route.query.total">
            {{ `Total of Semester ${form.semester} Exam` }}
          </span>
          <span v-else-if="$route.query.type">
            {{
              $route.query.type !== "0"
                ? `Monthly Exam of ${month_text}`
                : `Semester ${$route.query.semester} exam`
            }}
          </span>
        </div>
        <table
          style="
            width: 50%;
            font-family: Khmer OS Battambang;
            border-collapse: collapse;
            padding: 5px;
            color: black;
            float: left;
          "
        >
          <tr>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              ល.រ
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 15%;
              "
            >
              គោត្តនាម និងនាម
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              ម.ភាគ
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              ចំ.ថ្នាក់
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              និទ្ទេស
            </th>
          </tr>
          <tbody>
            <tr v-for="(ret, index) in form.details[0]" :key="index">
              <td style="text-align: center; background-color: #fcd48a">
                <span>{{ index + 1 }}</span>
              </td>
              <td
                style="
                  text-align: left;
                  font-family: Khmer OS Battambang;
                  padding-left: 5px;
                "
              >
                <span>{{ ret.name }}</span>
              </td>
              <td style="text-align: center">
                <span>{{ ret.avg?.toFixed(2) }}</span>
              </td>
              <td style="text-align: center; color: red">
                <span>{{ ret.rank }}</span>
              </td>
              <td style="text-align: center; font-family: Khmer OS Battambang">
                {{ ret.grade }}
              </td>
            </tr>
          </tbody>
        </table>
        <table
          style="
            width: 50%;
            font-family: Khmer OS Battambang;
            border-collapse: collapse;
            padding: 5px;
            color: black;
            float: right;
          "
        >
          <tr>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              ល.រ
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 15%;
              "
            >
              គោត្តនាម និងនាម
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              ម.ភាគ
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              ចំ.ថ្នាក់
            </th>
            <th
              style="
                text-align: center;
                background-color: #fcd48a;
                font-weight: bold;
                font-family: Khmer Os Muol Light;
                width: 5%;
              "
            >
              និទ្ទេស
            </th>
          </tr>
          <tbody>
            <tr v-for="(ret, index) in form.details[1]" :key="index">
              <td style="text-align: center; background-color: #fcd48a">
                <span>{{ Number(index) + 36 }}</span>
              </td>
              <td
                style="
                  text-align: left;
                  font-family: Khmer OS Battambang;
                  padding-left: 5px;
                "
              >
                <span>{{ ret.name }}</span>
              </td>
              <td style="text-align: center">
                <span>{{ ret.avg?.toFixed(2) }}</span>
              </td>
              <td style="text-align: center; color: red">
                <span>{{ ret.rank }}</span>
              </td>
              <td style="text-align: center; font-family: Khmer OS Battambang">
                {{ ret.grade }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- </q-card> -->
    </div>
  </div>
</template>

<script>
import axiosApiInstance from "../../../utilites";
import { Printd } from "printd";

export default {
  data: () => ({
    form: {
      details: [],
    },
    subjects: [],
    model: {},
    listing: false,
    search: "",
    searching: false,
    month_text: "",
    is_searched: false,
  }),
  mounted() {
    this.d = new Printd();
    const { contentWindow } = this.d.getIFrame();

    if (contentWindow) {
      contentWindow.addEventListener("beforeprint", () =>
        console.log("before print event!")
      );
      contentWindow.addEventListener("afterprint", () =>
        console.log("after print event!")
      );
    }
    this.fetchData();
    this.onSearch();
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
    "form.type"(newValue) {
      if (newValue) {
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
        .post(`study-classes/${this.$route.params.studyclass}/scores/ranking`, {
          type: this.$route.query.type,
          semester: this.$route.query.semester,
          total: this.$route.query.total,
        })
        .then((res) => {
          this.form.details = res.data.data;
          this.subjects = res.data.subjects;
          this.form.semester = res.data.semester;
          this.month_text = res.data.month_text;
        })
        .finally(() => {
          this.searching = false;
          this.is_searched = true;
        });
    },
    print() {
      this.d.print(document.getElementById("table"), [
        `table {
              border-collapse: collapse;
          }
          table,
          th,
          td {
              border: 2px solid gray;
          }`,
      ]);
    },
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
}
table,
th,
td {
  border: 2px solid gray;
}
</style>
