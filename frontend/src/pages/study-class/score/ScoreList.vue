<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          List Score
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
            <div class="col-md-4"></div>

            <div class="col-md-4">
              <q-select
                v-model="form.type"
                map-options
                emit-value
                :options="types"
                option-value="id"
                option-label="name"
                outlined
                label="Type"
                dense
              />
            </div>
            <div class="col-md-4" align="right">
              <q-btn label="Print" color="grey" @click="print" v-if="is_searched" />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
    <div class="q-px-lg" v-if="is_searched">
      <q-card class="q-pa-lg">
        <div id="table">
          <div
            style="
              text-align: center;
              font-weight: bold;
              margin-bottom: 15px;
              font-size: 20x;
            "
          >
            {{
              month_text != null
                ? `Monthly Exam of ${month_text}`
                : `Semester ${form.semester} Exam`
            }}
          </div>
          <table
            style="
              width: 100%;
              font-family: Khmer OS Battambang;
              border-collapse: collapse;
              padding: 5px;
              color: black;
            "
          >
            <thead>
              <tr>
                <th
                  style="
                    text-align: center;
                    height: 150px;
                    background-color: orange;
                    font-weight: bold;
                    font-family: Khmer Os Muol Light;
                    writing-mode: vertical-lr;
                    transform: rotate(180deg);
                    width: 5%;
                  "
                >
                  លេខរៀង
                </th>
                <th
                  style="
                    text-align: center;
                    height: 150px;
                    background-color: orange;
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
                    height: 150px;
                    background-color: orange;
                    font-weight: bold;
                    font-family: Khmer Os Muol Light;
                    width: 5%;
                  "
                >
                  ភេទ
                </th>
                <th
                  style="
                    text-align: center;
                    height: 150px;
                    background-color: orange;
                    font-weight: bold;
                    font-family: Khmer Os Muol Light;
                    writing-mode: vertical-lr;
                    transform: rotate(180deg);
                  "
                  :style="`width: ${50 / subjects.length}%`"
                  v-for="subject in subjects"
                  :key="subject"
                >
                  {{ subject }}
                </th>
                <th
                  style="
                    text-align: center;
                    height: 150px;
                    background-color: orange;
                    font-weight: bold;
                    font-family: Khmer Os Muol Light;
                    writing-mode: vertical-rl;
                    transform: rotate(180deg);
                    width: 5%;
                  "
                >
                  សរុប
                </th>
                <th
                  style="
                    text-align: center;
                    height: 150px;
                    background-color: orange;
                    font-weight: bold;
                    font-family: Khmer Os Muol Light;
                    writing-mode: vertical-rl;
                    transform: rotate(180deg);
                    width: 5%;
                  "
                >
                  មធ្យមភាគ
                </th>
                <th
                  style="
                    text-align: center;
                    height: 150px;
                    background-color: orange;
                    font-weight: bold;
                    font-family: Khmer Os Muol Light;
                    writing-mode: vertical-rl;
                    transform: rotate(180deg);
                    width: 5%;
                  "
                >
                  ចំណាត់ថ្នាក់
                </th>
                <th
                  style="
                    text-align: center;
                    height: 150px;
                    background-color: orange;
                    font-weight: bold;
                    font-family: Khmer Os Muol Light;
                    writing-mode: vertical-rl;
                    transform: rotate(180deg);
                    width: 10%;
                  "
                >
                  និទ្ទេស
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(ret, index) in form.details" :key="index">
                <td style="text-align: center">{{ index + 1 }}</td>
                <td
                  style="
                    text-align: left;
                    font-family: Khmer OS Battambang;
                    padding-left: 5px;
                  "
                >
                  {{ ret.name }}
                </td>
                <td style="text-align: center; font-family: Khmer OS Battambang">
                  {{ ret.gender }}
                </td>
                <td style="text-align: center" v-for="item in ret.details" :key="item">
                  {{ item.score || 0 }}
                </td>
                <td style="text-align: center">{{ ret.total.toFixed(2) }}</td>
                <td style="text-align: center">{{ ret.avg.toFixed(2) }}</td>
                <td style="text-align: center; color: red">{{ ret.rank }}</td>
                <td
                  style="text-align: center; font-family: Khmer OS Battambang"
                  v-if="ret.avg >= 9.5"
                >
                  ល្អណាស់
                </td>
                <td
                  style="text-align: center; font-family: Khmer OS Battambang"
                  v-else-if="ret.avg >= 8.0 && ret.avg < 9.5"
                >
                  ល្អ
                </td>
                <td
                  style="text-align: center; font-family: Khmer OS Battambang"
                  v-else-if="ret.avg > 6.5 && ret.avg < 8.0"
                >
                  ល្អបង្គួរ
                </td>
                <td
                  style="text-align: center; font-family: Khmer OS Battambang"
                  v-else-if="ret.avg >= 5.0 && ret.avg < 6.5"
                >
                  មធ្យម
                </td>
                <td style="text-align: center; font-family: Khmer OS Battambang" v-else>
                  ខ្សោយ
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </q-card>
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
      type: null,
      semester: 1,
    },
    subjects: [],
    model: {},
    listing: false,
    search: "",
    searching: false,
    month_text: "",
    is_searched: false,
    types: [
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
      {
        id: 13,
        name: "Semester 1",
      },
      {
        id: 14,
        name: "Semester 2",
      },
    ],
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
      let type = this.form.type;
      let semester = null;
      if (this.form.type == 13) {
        type = "0";
        semester = 1;
      } else if (this.form.type == 14) {
        type = "0";
        semester = 2;
      }
      axiosApiInstance
        .post(`study-classes/${this.$route.params.studyclass}/scores/list`, {
          type: type,
          semester: semester,
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
