<!-- កុំព្យូទ័រ -->
<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/plugins/utilites'
import { Printd } from 'printd'
import { DataRankings, GradePlus } from '@/helper/calculate-score'
const route = useRoute()
const model = ref({})
const data = ref([])
const refForm = ref()
const d = new Printd()
const onPrint = () => {
  d.print(document.getElementById('table'))
}
const fetchData = () => {
  api
    .post('academic-classes-detail', {
      id: route.query.id,
    })
    .then(res => {
      model.value = res.data.model
    })
  fetchTable()
}

const fetchTable = () => {
  api
    .post('exam-show', {
      academic_class_id: route.query.id,
    })
    .then(res => {
      data.value = res.data.data
      DataRankings(data) //ranking
    })
}

onMounted(() => {
  fetchData()
})
</script>
<template>
  <div>
    <VRow>
      <VCol
        cols="12"
        md="12"
        sm="12"
      >
        <v-form
          lazy-validation
          ref="refForm"
          @submit.prevent="submit()"
        >
          <VCard :title="`Class: ${model?.name} ( ${model?.academic_year?.name} )`">
            <VDivider />
            <v-btn
              class="mt-5 mx-5"
              color="secondary"
              variant="outlined"
              @click="$router.go(-1)"
              ><v-icon>mdi-arrow-back</v-icon>&nbsp; Back</v-btn
            >
            <VCardText>
              <v-row class="text-h6 font-weight-bold text-center my-5 mx-3">
                <div style="width: 40%"></div>
                <v-row style="width: 60%">
                  <!-- <div>
                    
                  </div> -->
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
                    <td>
                      <VRow>
                        <VCol style="margin: 0 90%"
                          ><v-img
                            src="/src/assets/images/logo.png"
                            :width="100"
                          ></v-img>
                        </VCol>
                      </VRow>
                    </td>

                    <td colspan="16"></td>
                    <td
                      colspan="9"
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
                      colspan="4"
                      valign="center"
                      style="
                        text-align: center;
                        font-weight: bold;
                        line-height: 30px;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      Aide volontair aux
                    </td>
                    <td colspan="3"></td>
                    <td colspan="10"></td>
                    <td
                      colspan="3"
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
                      colspan="4"
                      valign="center"
                      style="
                        text-align: center;
                        font-weight: bold;
                        line-height: 30px;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      enfants du Cambodage
                    </td>
                    <td colspan="3"></td>
                    <td colspan="10"></td>
                    <td
                      colspan="3"
                      valign="center"
                      style="
                        text-align: center;
                        font-weight: bold;
                        line-height: 30px;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      6
                    </td>
                  </tr>
                  <tr>
                    <td
                      colspan="4"
                      style="
                        text-align: center;
                        font-weight: bold;
                        line-height: 30px;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      6
                    </td>
                    <td
                      colspan="12"
                      style="
                        text-align: center;
                        font-weight: bold;
                        line-height: normal;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      TERM RESULT កុំព្យូទ័រ
                      <!-- {{ exam_month.id != 0 ? 'ខែ' : '' }}{{ exam_month.name
                      }}{{ params_s ? 'លើកទី' + params_s : '' }} -->
                    </td>
                  </tr>

                  <tr style="line-height: 30px">
                    <td
                      colspan="1"
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      Teacher :
                    </td>
                    <td
                      colspan="6"
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      Room :
                    </td>
                  </tr>
                  <tr style="line-height: 40px">
                    <td
                      colspan="1"
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      Level &nbsp;&nbsp;&nbsp;:
                    </td>
                    <td
                      colspan="6"
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      Time &nbsp;:
                    </td>
                  </tr>
                  <tr >
                    <th
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      N<sup>o</sup>
                    </th>
                    <th
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                      colspan="7"
                    >
                      Name
                    </th>
                    <th
                      rowspan="2"
                      colspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Sex
                    </th>
                    <!-- <th
                      rowspan="2"
                      colspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Att
                    </th> -->
                    <!-- <th
                      colspan="10"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Score
                    </th> -->
                    <th
                      colspan="2"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Final
                    </th>
                    <!-- <th
                      colspan="2"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Ave.
                    </th>

                    <th
                      colspan="2"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Rank
                    </th>
                    <th
                      colspan="2"
                      style="border: 1px solid black; padding: 5px"
                      rowspan="2"
                    >
                      Result
                    </th> -->
                  </tr>
                  <!-- <tr>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Att
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Quiz
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      HW
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Re
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Voc.
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Gr.
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      LIU
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Wr
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Li
                    </th>
                    <th
                      style="border: 1px solid black; padding: 5px"
                      colspan="1"
                    >
                      Sp.
                    </th>
                  </tr> -->
                </thead>
                <tbody>
                  <tr
                    v-for="(ret, index) in data"
                    :key="index"
                  >
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ index + 1 }}
                    </td>
                    <td
                      colspan="3"
                      style="border: 1px solid black; padding: 5px"
                    >
                      {{ ret.last_name + ' ' + ret.first_name }}
                    </td>
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.gender == 1 ? 'ប្រុស' : 'ស្រី' }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.att }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.quiz }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.hw }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.re }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.voc }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.gr }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.liu }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.wr }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.li }}
                    </td>
                    <td
                      colspan="1"
                      style="text-align: center; border: 1px solid black; padding: 5px"
                    >
                      {{ ret.sp }}
                    </td>
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.total }}
                    </td>
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.total / 10 }}
                    </td>
                    <!-- rank -->
                    <td style="text-align: center; border: 1px solid black; padding: 5px">
                      {{ ret.rank }}
                    </td>

                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                    >
                      {{ GradePlus(ret.total) }}
                    </td>
                  </tr>
                  <td
                    style="height: 45px"
                    colspan="7"
                  ></td>
                  <td colspan="2">
                    <table>
                      <div
                        style="
                          width: 250%;
                          height: 2vh;
                          font-weight: bold;
                          font-size: 16px;
                          font-family: 'Times New Roman', Times, serif;
                        "
                      >
                        <div style="padding: 0 5% 0 5%; border: 1px solid">
                          <tr>
                            <td>Note:</td>
                          </tr>
                          <tr>
                            <td>1. A+ =98-100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>8. E = 50-64</td>
                          </tr>
                          <tr>
                            <td>2. A =90-97</td>
                            <td>9. F = 0-49</td>
                          </tr>
                          <tr>
                            <td>3. B+ =85-89</td>
                          </tr>
                          <tr>
                            <td>4. B =80-84</td>
                          </tr>
                          <tr>
                            <td>5. C+ =75-79</td>
                          </tr>
                          <tr>
                            <td>6. C =70-74</td>
                          </tr>
                          <tr>
                            <td>7. D =65-69</td>
                          </tr>
                        </div>
                      </div>
                    </table>
                  </td>
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
                      Date ........./................/2023
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
                    <td colspan="13"></td>
                    <td
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                      colspan="18"
                    >
                      Date ......./.........................../2023
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
                      colspan="13"
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
                      colspan="15"
                    ></td>
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
                      <!-- {{ model.teacher?.name }} -->
                      Smey Need You
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
        </v-form>
      </VCol>
    </VRow>
  </div>
</template>
<route lang="yaml">
meta:
  title: Score
  layout: default
  subject: Auth
  active: 'academic-class'
</route>
