<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          Report Attendance
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
        <q-card class="q-mt-md q-pa-md">
          <ValidateForm ref="form_ref" :validation-schema="rules">
            <form @submit.prevent="onSearch">
              <div class="row">
                <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                  <ValidateField
                    name="month"
                    v-model="form.month"
                    v-slot="{ value, field, errorMessage }"
                  >
                    <q-select
                      v-model="form.month"
                      map-options
                      emit-value
                      :options="months"
                      :error="!!errorMessage"
                      :error-message="errorMessage"
                      :model-value="value"
                      v-bind="field"
                      option-value="id"
                      option-label="name"
                      outlined
                      label="Month"
                      dense
                    />
                  </ValidateField>
                </div>
              </div>
            </form>
          </ValidateForm>
          <div class="col-md-4" align="right">
            <q-btn label="Print" color="grey" @click="print" />
          </div>
        </q-card>
        <q-card class="q-mt-md q-pa-md" id="table">
          <div style="text-align: center; margin-bottom: 30px; font-weight: bold">
            Attendance Report of {{ month_text ? `${month_text}` : `Year` }}
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
                <!-- <th class="text-left">No</th> -->
                <th class="text-center">Code</th>
                <th class="text-center">Name</th>
                <th class="text-center">Gender</th>
                <th class="text-center">Permission</th>
                <th class="text-center">No Permission</th>
                <th class="text-center">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(ret, index) in data" :key="index">
                <!-- <td class="text-left">{{ index + 1 }}</td> -->
                <td
                  v-for="(item, i) in ret"
                  :key="i"
                  :style="i == 1 ? 'padding-left: 5px' : 'text-align: center'"
                >
                  {{ item }}
                </td>
              </tr>
            </tbody>
          </table>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script>
import axiosApiInstance from "../../../utilites";
import { Form as ValidateForm, Field as ValidateField } from "vee-validate";
import { string, object } from "yup";
import dayjs from "dayjs";
import { Printd } from "printd";
export default {
  components: {
    ValidateField,
    ValidateForm,
  },
  data: () => ({
    form: {
      month: null,
    },
    months: [
      {
        id: "1",
        name: "January",
      },
      {
        id: "2",
        name: "February",
      },
      {
        id: "3",
        name: "March",
      },
      {
        id: "4",
        name: "April",
      },
      {
        id: "5",
        name: "May",
      },
      {
        id: "6",
        name: "June",
      },
      {
        id: "7",
        name: "July",
      },
      {
        id: "8",
        name: "August",
      },
      {
        id: "9",
        name: "September",
      },
      {
        id: "10",
        name: "October",
      },
      {
        id: "11",
        name: "November",
      },
      {
        id: "12",
        name: "December",
      },
    ],
    model: {},
    data: [],
    listing: false,
    search: "",
    searching: false,
    month_text: "",
  }),
  mounted() {
    this.d = new Printd();
    this.fetchData();
    this.onSearch();
  },
  watch: {
    "form.month"(newValue) {
      this.onSearch();
    },
  },
  computed: {
    rules() {
      return object({
        month: string().required().label("Month"),
      });
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
        .post(
          `study-classes/${this.$route.params.studyclass}/attendances/report`,
          this.form
        )
        .then((res) => {
          this.data = res.data.data;
          this.month_text = res.data.month_text;
        })
        .finally(() => {
          this.searching = false;
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
