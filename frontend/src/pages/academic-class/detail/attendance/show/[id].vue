<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/plugins/utilites"
const { params } = useRoute();
import { Printd } from "printd";
const d = new Printd();
const params_id = ref(null);
const params_month = ref(null);
const params_s = ref(null);
const router = useRouter();
const model = ref({});
const exam_month = ref({});
const data = ref({
  month: null,
  total_day: 31,
  attendances: [],
});

const onPrint = () => {
  d.print(document.getElementById("table"));
};

const refForm = ref();

const fetchData = () => {
  api
    .post("academic-classes-detail", {
      id: params_id.value,
    })
    .then((res) => {
      model.value = res.data.model;
    });
  fetchTable();
};

const fetchTable = () => {
  api
    .post("attendances-show", {
      academic_class_id: params_id.value,
      month: params_month.value,
    })
    .then((res) => {
      data.value = res.data.records;
    });
};

const semesters = ref([
  {
    id: 1,
    name: "ឆមាសទី១",
  },
  {
    id: 2,
    name: "ឆមាសទី២",
  },
]);

const months = ref([
  {
    id: 1,
    name: "មករា",
  },
  {
    id: 2,
    name: "កុម្ភៈ",
  },
  {
    id: 3,
    name: "មីនា",
  },
  {
    id: 4,
    name: "មេសា",
  },
  {
    id: 5,
    name: "ឧសភា",
  },
  {
    id: 6,
    name: "មិថុនា",
  },
  {
    id: 7,
    name: "កក្កដា",
  },
  {
    id: 8,
    name: "សីហា",
  },
  {
    id: 9,
    name: "កញ្ញា",
  },
  {
    id: 10,
    name: "តុលា",
  },
  {
    id: 11,
    name: "វិច្ឆិកា",
  },
  {
    id: 12,
    name: "ធ្នូ",
  },
  {
    id: 0,
    name: "ឆមាស",
  },
]);

onMounted(() => {
  [params_id.value, params_month.value, params_s.value] = params.id.split("_");
  months.value.filter((e) => {
    if (e.id == params_month.value) {
      exam_month.value = e;
    }
  });
  fetchData();
});
</script>
<template>
  <div>
    <VRow>
      <VCol cols="12" md="12" sm="12">
        <VCard
          :title="`ថ្នាក់ទី ${model?.name} ឆ្នាំសិក្សា ${model?.academic_year?.name}`"
        >
          <VDivider />
          <v-btn
            class="mt-5 mx-5"
            color="secondary"
            variant="outlined"
            @click="$router.go(-1)"
            ><v-icon>mdi-arrow-back</v-icon>&nbsp;ថយក្រោយ</v-btn
          >
          <VCardText>
            <v-row class="text-h6 font-weight-bold text-center my-5 mx-3">
              <div style="width: 40%"></div>
              <v-row style="width: 60%">
                <v-spacer />
                <div>
                  <v-btn flat color="white" @click="onPrint"
                    ><v-icon color="grey">mdi-printer</v-icon></v-btn
                  >
                </div>
              </v-row>
            </v-row>
            <table
              style="
                width: 100%;
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                padding: 0;
                text-align: center;
                font-family: Khmer OS Battambang;
                border-collapse: collapse;
                padding: 5px;
                color: black;
              "
              class="mt-5"
              id="table"
            >
              <colgroup>
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
                <col width="2%" />
              </colgroup>

              <thead>
                <tr>
                  <td colspan="12"></td>
                  <td colspan="28"></td>
                  <td
                    colspan="10"
                    valign="center"
                    style="
                      text-align: center;
                      font-family: Khmer Os Muol Light;
                      line-height: normal;
                      font-size: 12px;
                    "
                  >
                    ព្រះរាជាណាចក្រកម្ពុជា
                  </td>
                </tr>
                <tr>
                  <td
                    colspan="12"
                    valign="center"
                    style="
                      text-align: center;
                      font-family: Khmer Os Muol Light;
                      line-height: normal;
                      font-size: 12px;
                    "
                  >
                    ខេត្តបាត់ដំបង
                  </td>
                  <td colspan="28"></td>
                  <td
                    colspan="10"
                    valign="center"
                    style="
                      text-align: center;
                      font-family: Khmer Os Muol Light;
                      line-height: normal;
                      font-size: 12px;
                    "
                  >
                    ជាតិ សាសនា ព្រះមហាក្សត្រ
                  </td>
                </tr>
                <tr>
                  <td
                    colspan="12"
                    valign="center"
                    style="
                      text-align: center;
                      font-family: Khmer Os Muol Light;
                      line-height: normal;
                      font-size: 12px;
                    "
                  >
                    វិទ្យាល័យព្រៃសង្ហា
                  </td>
                  <td colspan="28"></td>
                  <td
                    colspan="10"
                    valign="center"
                    style="
                      text-align: center;
                      font-family: Tacteing;
                      line-height: normal;
                      font-size: 12px;
                    "
                  >
                    6
                  </td>
                </tr>
                <tr>
                  <td
                    colspan="50"
                    style="
                      text-align: center;
                      font-family: Khmer Os Muol Light;
                      line-height: normal;
                      font-size: 12px;
                    "
                  >
                    តារាងអវត្តមានប្រចាំ{{ exam_month.id != 0 ? "ខែ" : ""
                    }}{{ exam_month.name }}{{ params_s ? "លើកទី" + params_s : "" }}
                  </td>
                </tr>
                <tr>
                  <td
                    colspan="50"
                    style="
                      text-align: center;
                      font-family: Tacteing;
                      line-height: 2rem;
                      font-size: 12px;
                    "
                  >
                    ros
                  </td>
                </tr>
                <tr>
                  <!-- <th colspan="2">ល.រ</th> -->
                  <th
                    style="border: 1px solid black; padding: 5px"
                    :colspan="
                      data?.attendances[0]?.student?.days?.length > 28 ? '15' : '19'
                    "
                  >
                    ឈ្មោះ
                  </th>
                  <th style="border: 1px solid black; padding: 5px" colspan="4">ភេទ</th>
                  <th
                    style="border: 1px solid black; padding: 5px"
                    v-for="date in data.total_day"
                    :key="date"
                  >
                    {{ date.toString().length == "1" ? "0" + date : date }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(student, index) in data.attendances" :key="index">
                  <!-- <td colspan="2" class="text-center">
                      {{ student.number }}
                    </td> -->
                  <td
                    style="border: 1px solid black; padding: 5px text-align: left; padding-left: 3px"
                    :colspan="student?.days?.length > 28 ? '15' : '19'"
                  >
                    {{ index }}
                  </td>
                  <td
                    colspan="4"
                    class="text-center"
                    style="border: 1px solid black; padding: 5px"
                  >
                    {{ student.sex == 1 ? "ប្រុស" : "ស្រី" }}
                  </td>
                  <td
                    v-for="date in student.days"
                    :key="date"
                    :style="date.absent == 'P' ? 'color: orange' : 'color:red'"
                    style="border: 1px solid black; padding: 5px"
                  >
                    {{ date.absent }}
                  </td>
                </tr>
                <tr>
                  <td colspan="30"></td>
                  <td
                    style="
                      text-align: center;
                      font-family: Khmer OS Battambang;
                      line-height: normal;
                    "
                    colspan="20"
                  >
                    ថ្ងៃ
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ខែ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ឆ្នាំ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ព.ស.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  </td>
                </tr>
                <tr>
                  <td colspan="30"></td>
                  <td
                    style="text-align: center; font-family: Khmer OS Battambang"
                    colspan="20"
                  >
                    ធ្វើនៅបាត់ដំបង, ថ្ងៃទី&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ខែ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ឆ្នាំ២០
                  </td>
                </tr>
                <tr>
                  <td colspan="30"></td>
                  <td
                    style="text-align: center; font-family: Khmer OS Battambang"
                    colspan="20"
                  >
                    ហត្ថលេខាគ្រូបន្ទុក
                  </td>
                </tr>
                <tr>
                  <td colspan="30"></td>
                  <td
                    style="
                      text-align: center;
                      font-family: Khmer OS Battambang;
                      line-height: 120px;
                    "
                    colspan="20"
                  >
                    {{ model.teacher?.name }}
                  </td>
                  <td style="text-align: center" colspan="2"></td>
                </tr>
              </tbody>
            </table>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>
