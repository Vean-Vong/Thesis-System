<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/plugins/utilites'
const { params } = useRoute()
import { Printd } from 'printd'
const d = new Printd()
const params_id = ref(null)
const params_month = ref(null)
const params_s = ref(null)
const router = useRouter()
const model = ref({})
const exam_month = ref({})
const data = ref({
  month: null,
  total_day: 31,
  attendances: [],
})
const imgP = `
  table{
    margin-top: -20px;
    margin: 0;
  }
  img{
    margin: 0;
    width: 90px;
    height: auto;
    max-width: none;
  }`
const onPrint = () => {
  d.print(document.getElementById('table'), [imgP])
}
const khmerToEnglishMonthMap = {
  "មករា": "January",
  "កុម្ភៈ": "February",
  "មីនា": "March",
  "មេសា": "April",
  "ឧសភា": "May",
  "មិថុនា": "June",
  "កក្កដា": "July",
  "សីហា": "August",
  "កញ្ញា": "September",
  "តុលា": "October",
  "វិច្ឆិកា": "November",
  "ធ្នូ": "December",
  "ឆមាស": "Semester"
};

const convertKhmerMonthToEnglish = (khmerMonth) => khmerToEnglishMonthMap[khmerMonth] || "Unknown Month";

const refForm = ref()

const fetchData = () => {
  api
    .post('academic-classes-detail', {
      id: params_id.value,
    })
    .then(res => {
      model.value = res.data.model
    })
  fetchTable()
}

const fetchTable = () => {
  api
    .post('attendances-show', {
      academic_class_id: params_id.value,
      month: params_month.value,
    })
    .then(res => {
      data.value = res.data.records
    })
}

// const semesters = ref([
//   {
//     id: 1,
//     name: 'ឆមាសទី១',
//   },
//   {
//     id: 2,
//     name: 'ឆមាសទី២',
//   },
// ])

const months = ref([
  {
    id: 1,
    name: 'មករា',
  },
  {
    id: 2,
    name: 'កុម្ភៈ',
  },
  {
    id: 3,
    name: 'មីនា',
  },
  {
    id: 4,
    name: 'មេសា',
  },
  {
    id: 5,
    name: 'ឧសភា',
  },
  {
    id: 6,
    name: 'មិថុនា',
  },
  {
    id: 7,
    name: 'កក្កដា',
  },
  {
    id: 8,
    name: 'សីហា',
  },
  {
    id: 9,
    name: 'កញ្ញា',
  },
  {
    id: 10,
    name: 'តុលា',
  },
  {
    id: 11,
    name: 'វិច្ឆិកា',
  },
  {
    id: 12,
    name: 'ធ្នូ',
  },
  {
    id: 0,
    name: 'ឆមាស',
  },
])
const storeID = ref([
  {
    id: 1,
    name: '1',
  },
  {
    id: 2,
    name: '2',
  },
  {
    id: 3,
    name: '3',
  },
  {
    id: 4,
    name: '4',
  },
  {
    id: 5,
    name: '5',
  },
  {
    id: 6,
    name: '6',
  },
])
onMounted(() => {
  [params_id.value, params_month.value, params_s.value] = params.id.split('_');
  exam_month.value = months.value.find(e => e.id == params_month.value) || {};
  fetchData();
});

const currentYear = computed(() => new Date().getFullYear());
const monthDisplay = computed(() => {
  return `${exam_month.value.id !== 0 ? ' ' : ''}${convertKhmerMonthToEnglish(exam_month.value.name)}${params_s.value ? ' Exam ' + params_s.value : ''}`;
});
</script>
<template>
  <div>
    <VRow>
      <VCol
        cols="12"
        md="12"
        sm="12"
      >
        <VCard :title="`ថ្នាក់ទី ${model?.name} ឆ្នាំសិក្សា ​${model?.academic_year?.name}`">
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
                  <v-btn
                    flat
                    color="white"
                    @click="onPrint"
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
                  <td>
                    <VRow>
                      <VCol style="margin: 0 85%"
                        ><v-img
                          src="/src/assets/images/logo.png"
                          :width="100"
                        ></v-img>
                      </VCol>
                    </VRow>
                  </td>

                  <td colspan="18"></td>
                  <td colspan="24"></td>
                  <td
                    colspan="8"
                    valign="bottom"
                    style="
                      text-align: center;
                      font-weight: bold;
                      line-height: 30px;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    KINGDOM OF CAMBODIA
                  </td>
                </tr>
                <tr>
                  <td
                    colspan="9"
                    valign="center"
                    style="
                      text-align: center;
                      font-weight: bold;
                      line-height: 30px;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  > 
                  
                    ជំនួយស្ម័គ្រចិត្តដល់កុមារកម្ពុជា
                  </td>
                  <td colspan="17"></td>
                  <td colspan="17"></td>
                  <td
                    colspan="8"
                    valign="center"
                    style="
                      text-align: center;
                      font-weight: bold;
                      line-height: 30px;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    NATION RELIGION KING
                  </td>
                </tr>
                <tr>
                  <td
                    colspan="9"
                    valign="center"
                    style="
                      text-align: center;
                      font-weight: bold;
                      line-height: 30px;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                  Aide volontair aux enfants du Cambodage
                  </td>
                  <td colspan="18"></td>
                  <td colspan="20"></td>
                </tr>
                <tr>

                  <td colspan="12"></td>
                  <td
                    colspan="23"
                    style="
                      text-align: center;
                      font-weight: bold;
                      line-height: normal;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    វត្តមាន
                    {{exam_month.id != 0 ? 'ខែ' : '' }}{{ exam_month.name }}{{ params_s ? 'លើកទី' + params_s : '' }}
                  </td>
                </tr>
                <tr>
                  <td colspan="12"></td>
                  <td
                    colspan="23"
                    style="
                      text-align: center;
                      font-weight: bold;
                      line-height: 40px;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    Attendance
                    {{ monthDisplay }}</td>
                </tr>
                <br />
                <tr style="line-height: 30px">
                  <td
                    colspan="2"
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    Teacher : {{ model.teacher?.name }}
                  </td>
                  <td
                    colspan="4"
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Room : {{ model.room?.room }}
                  </td>
                </tr>
                <tr style="line-height: 40px">
                  <td
                    colspan="2"
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    &nbsp;&nbsp; Level &nbsp;: {{ model.name }}
                  </td>
                  <td
                    colspan="4"
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                  >
                    &nbsp; Time &nbsp;: {{ model.time?.time }}
                  </td>
                </tr>
                  <tr>
                    <td
                      colspan="1"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px ;font-weight: bold;"
                    >
                      ល.រ
                    </td>
                    <td
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px ;font-weight: bold;"
                      :colspan="data?.attendances[0]?.student?.days?.length > 28 ? '11' : '11'"
                    >
                      ឈ្មោះ
                    </td>
                    <td
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px ;font-weight: bold;"
                      colspan="3"
                    >
                      ភេទ
                    </td>
                    <td
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px ;font-weight: bold;"
                      v-for="date in data.total_day"
                      :key="date"
                    >
                      {{ date.toString().length == '1' ? '0' + date : date }}
                    </td>
                    <td
                      colspan="4"
                      style="border: 1px solid black; padding: 5px ;font-weight: bold;"
                    >
                      Total
                    </td>
                  </tr>
                  <tr>
                    <td
                    colspan="2"
                      style="border: 1px solid black; padding: 5px ;font-weight: bold;"
                    >
                      A
                    </td>
                    <td
                    colspan="2"
                      style="border: 1px solid black; padding: 5px ;font-weight: bold;"
                    >
                      P
                    </td>
                  </tr>
                </thead>                
              <tbody>
                <tr
                  v-for="(student, index) in data.attendances"
                  :key="index"
                >
                  <td
                    class="text-center;"
                    style="border: 1px solid black; padding: 5px"
                  >
                    {{ student.number }}
                  </td>
                  <td
                    style="border: 1px solid black; padding: 5px; text-align: left; padding-left: 3px"
                    :colspan="student?.days?.length > 28 ? '11' : '11'"
                  >
                    {{ index }}
                  </td>
                  <td
                    colspan="3"
                    class="text-center"
                    style="border: 1px solid black; padding: 5px"
                  >
                    {{ student.gender == 1 ? 'ប្រុស' : 'ស្រី' }}
                  </td>
                  <td
                    v-for="date in student.days"
                    :key="date"
                    :style="date.absent == 'P' ? 'color: orange' : 'color:red'"
                    style="border: 1px solid black; padding: 5px"
                  >
                    {{ date.absent }}
                  </td>
                  <td
                    colspan="2"
                    class="text-center"
                    style="border: 1px solid black; padding: 5px"
                  >
                  0
                  </td>
                  <td
                    colspan="2"
                    class="text-center"
                    style="border: 1px solid black; padding: 5px"
                  >
                    0
                  </td>
                </tr>
                <br />
                <tr>
                  <td
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                    colspan="2"
                  >
                    Date ........./................/{{currentYear}}
                  </td>
                </tr>
                <tr>
                  <td
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                    colspan="2"
                  >
                    Seen and approved
                  </td>
                  <td colspan="20"></td>
                  <td colspan="20"></td>
                  <td
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                    colspan="24"
                  >
                    Date ......./.........................../{{currentYear}}
                  </td>
                </tr>
                <tr>
                  <td
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                    colspan="2"
                  >
                    Director
                  </td>
                  <td
                    style="text-align: center"
                    colspan="20"
                  ></td>
                  <td
                    style="text-align: center"
                    colspan="20"
                  ></td>
                  <td
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                    "
                    colspan="16"
                  >
                    TEACHER
                  </td>
                </tr>
                <tr>
                  <td
                    style="text-align: center"
                    colspan="10"
                  ></td>
                  <td
                    style="text-align: center; font-family: Khmer OS Battambang"
                    colspan="10"
                  >
                    <!-- ហត្ថលេខាគ្រូបន្ទុក -->
                  </td>
                </tr>
                <tr>
                  <td
                    style="text-align: center; height: 80px"
                    colspan="22"
                  ></td>
                  <td colspan="20"></td>
                  <td
                    style="
                      text-align: center;
                      font-weight: bold;
                      font-size: 16px;
                      font-family: 'Times New Roman', Times, serif;
                      height: 150px;
                    "
                    colspan="10"
                  >
                    {{ model.teacher?.name }}
                    <!-- Smey Need You -->
                  </td>
                  <td
                    style="text-align: center"
                    colspan="2"
                  ></td>
                </tr>
              </tbody>
            </table>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>
<route lang="yaml">
meta:
  title: Attendance
  layout: default
  subject: Auth
  active: 'academic-class'
</route>
