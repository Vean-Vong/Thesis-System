<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          Create Attendance
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
                    name="date"
                    v-model="form.date"
                    v-slot="{ value, field, errorMessage }"
                  >
                    <q-input
                      v-model="form.date"
                      :error="!!errorMessage"
                      :error-message="errorMessage"
                      :model-value="value"
                      v-bind="field"
                      outlined
                      dense
                      type="date"
                      label="Date"
                    ></q-input>
                  </ValidateField>
                </div>
                <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                  <ValidateField
                    name="meridiem"
                    v-model="form.meridiem"
                    v-slot="{ value, field, errorMessage }"
                  >
                    <q-select
                      v-model="form.meridiem"
                      map-options
                      emit-value
                      :options="meridiems"
                      :error="!!errorMessage"
                      :error-message="errorMessage"
                      :model-value="value"
                      v-bind="field"
                      option-value="id"
                      option-label="name"
                      outlined
                      label="Meridiem"
                      dense
                    />
                  </ValidateField>
                </div>
              </div>
            </form>
          </ValidateForm>
        </q-card>
        <q-card class="q-mt-md q-pa-md">
          <q-markup-table>
            <thead>
              <tr>
                <th class="text-left">No</th>
                <th class="text-center">Name</th>
                <th class="text-center">Sex</th>
                <th class="text-center">Absence</th>
                <th class="text-center">Reason</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(ret, index) in form.details" :key="index">
                <td class="text-left">{{ index + 1 }}</td>
                <td class="text-center">{{ ret.full_name }}</td>
                <td class="text-center">{{ ret.gender == 1 ? "ប្រុស" : "ស្រី" }}</td>
                <td class="text-center">
                  <q-radio v-model="ret.absent" :val="1" label="Absent" />
                  <q-radio v-model="ret.absent" :val="2" label="Permission" />
                </td>
                <td class="text-center">
                  <q-input
                    outlined
                    dense
                    type="text"
                    v-model="ret.reason"
                    label="Reason"
                  />
                </td>
              </tr>
            </tbody>
          </q-markup-table>
          <div class="q-my-md" align="right">
            <q-btn label="Save" color="green" icon="save" @click="save()" />
          </div>
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
export default {
  components: {
    ValidateField,
    ValidateForm,
  },
  data: () => ({
    form: {
      meridiem: 1,
      date: dayjs(new Date()).format("YYYY-MM-DD"),
      details: [],
    },
    model: {},
    listing: false,
    search: "",
    searching: false,

    meridiems: [
      {
        id: 1,
        name: "Morning",
      },
      {
        id: 2,
        name: "Afternoon",
      },
    ],
  }),
  mounted() {
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
      let { valid } = await this.$refs.form_ref.validate();
      if (valid) {
        this.searching = true;
        axiosApiInstance
          .post(
            `study-classes/${this.$route.params.studyclass}/attendances/form`,
            this.form
          )
          .then((res) => {
            this.form.details = res.data;
          })
          .finally(() => {
            this.searching = false;
          });
      }
    },

    async save() {
      axiosApiInstance
        .post(
          `study-classes/${this.$route.params.studyclass}/attendances/save`,
          this.form
        )
        .then((res) => {
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
