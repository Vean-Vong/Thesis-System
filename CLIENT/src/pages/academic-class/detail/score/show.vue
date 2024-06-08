<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/plugins/utilites"
import { Printd } from "printd";
const { params } = useRoute();
const params_id = ref(null);
const params_month = ref(null);
const params_s = ref(null);
const router = useRouter();
const route = useRoute();
const model = ref({});
const exam_month = ref({});
const data = ref([]);
const refForm = ref();
const d = new Printd();
const onPrint = () => {
  d.print(document.getElementById("table"));
};
const fetchData = () => {
  api
    .post("academic-classes-detail", {
      id: route.query.id,
    })
    .then((res) => {
      model.value = res.data.model;
    });
  fetchTable();
};

const fetchTable = () => {
  api
    .post("exam-show", {
      academic_class_id: route.query.id,
    })
    .then((res) => {
      data.value = res.data.data;
    });
};

onMounted(() => {
  fetchData();
});
</script>
<template>
  <div>
    <VRow>
      <VCol cols="12" md="12" sm="12">
        <v-form lazy-validation ref="refForm" @submit.prevent="submit()">
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
                  <!-- <div>
                    
                  </div> -->
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
                  font-family: Khmer OS Battambang;
                  border-collapse: collapse;
                  padding: 5px;
                  color: black;
                "
                id="table"
                class="mt-5"
              >
                <colgroup>
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                  <col width="5%" />
                </colgroup>
                <thead>
                  <tr>
                    <td colspan="7"></td>
                    <td colspan="10"></td>
                    <td
                      colspan="3"
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
                      colspan="4"
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
                    <td colspan="3"></td>
                    <td colspan="10"></td>
                    <td
                      colspan="3"
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
                      colspan="4"
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
                    <td colspan="3"></td>
                    <td colspan="10"></td>
                    <td
                      colspan="3"
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
                      colspan="21"
                      style="
                        text-align: center;
                        font-family: Khmer Os Muol Light;
                        line-height: normal;
                        font-size: 12px;
                      "
                    >
                      តារាងពិន្ទុប្រចាំ{{ exam_month.id != 0 ? "ខែ" : ""
                      }}{{ exam_month.name }}{{ params_s ? "លើកទី" + params_s : "" }}
                    </td>
                  </tr>
                  <tr>
                    <td
                      colspan="21"
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
                    <th rowspan="2" style="border: 1px solid black; padding: 5px">ល.រ</th>
                    <th
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                      colspan="3"
                    >
                      ឈ្មោះ
                    </th>
                    <th
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                      colspan="3"
                    >
                      ថ្ងៃខែឆ្នាំកំណើត
                    </th>
                    <th rowspan="2" style="border: 1px solid black; padding: 5px">ភេទ</th>
                    <th colspan="8" style="border: 1px solid black; padding: 5px">
                      ពិន្ទុ
                    </th>
                    <th
                      colspan="1"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      សរុប
                    </th>
                    <th
                      colspan="1"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      មធ្យមភាគ
                    </th>
                    <th
                      colspan="2"
                      style="border: 1px solid black; padding: 5px"
                      rowspan="2"
                    >
                      និទ្ទេស
                    </th>
                    <th
                      colspan="1"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      លទ្ធផល
                    </th>
                  </tr>
                  <tr>
                    <th style="border: 1px solid black; padding: 5px" colspan="2">
                      ភាសាខ្មែរ
                    </th>
                    <th style="border: 1px solid black; padding: 5px" colspan="2">
                      គណិតវិទ្យា
                    </th>
                    <th style="border: 1px solid black; padding: 5px" colspan="2">
                      វិទ្យាសាស្រ្ត
                    </th>
                    <th style="border: 1px solid black; padding: 5px" colspan="2">
                      សិក្សាសង្គម
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(ret, index) in data" :key="index">
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ index + 1 }}
                    </td>
                    <td colspan="3" style="border: 1px solid black; padding: 5px">
                      {{ ret.name }}
                    </td>
                    <td
                      colspan="3"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.dob }}
                    </td>
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.sex == 1 ? "ប្រុស" : "ស្រី" }}
                    </td>
                    <td
                      colspan="2"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.k }}
                    </td>
                    <td
                      colspan="2"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.m }}
                    </td>
                    <td
                      colspan="2"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.sc }}
                    </td>
                    <td
                      colspan="2"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.s }}
                    </td>
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.total }}
                    </td>
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.avg }}
                    </td>
                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                      v-if="ret.avg >= 9.5"
                    >
                      ល្អណាស់
                    </td>
                    <td
                      colspan="2"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      v-else-if="ret.avg >= 8.0 && ret.avg < 9.5"
                    >
                      ល្អ
                    </td>
                    <td
                      colspan="2"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      v-else-if="ret.avg > 6.5 && ret.avg < 8.0"
                    >
                      ល្អបង្គួរ
                    </td>
                    <td
                      colspan="2"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      v-else-if="ret.avg >= 5.0 && ret.avg < 6.5"
                    >
                      មធ្យម
                    </td>
                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                      v-else
                    >
                      ខ្សោយ
                    </td>
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.result }}
                    </td>
                  </tr>
                  <tr>
                    <td style="text-align: center" colspan="10"></td>
                    <td
                      style="
                        text-align: center;
                        font-family: Khmer OS Battambang;
                        line-height: normal;
                      "
                      colspan="10"
                    >
                      ថ្ងៃ
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      ខែ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      ឆ្នាំ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      ព.ស.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                  </tr>
                  <tr>
                    <td style="text-align: center" colspan="10"></td>
                    <td
                      style="text-align: center; font-family: Khmer OS Battambang"
                      colspan="10"
                    >
                      ធ្វើនៅបាត់ដំបង, ថ្ងៃទី&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      ខែ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      ឆ្នាំ២០
                    </td>
                  </tr>
                  <tr>
                    <td style="text-align: center" colspan="10"></td>
                    <td
                      style="text-align: center; font-family: Khmer OS Battambang"
                      colspan="10"
                    >
                      ហត្ថលេខាគ្រូបន្ទុក
                    </td>
                  </tr>
                  <tr>
                    <td style="text-align: center" colspan="10"></td>
                    <td
                      style="
                        text-align: center;
                        font-family: Khmer OS Battambang;
                        line-height: 120px;
                      "
                      colspan="10"
                    >
                      {{ model.teacher?.name }}
                    </td>
                    <td style="text-align: center" colspan="2"></td>
                  </tr>
                </tbody>
              </table>
            </VCardText>
          </VCard>
        </v-form>
      </VCol>
    </VRow>
  </div>
</template>
