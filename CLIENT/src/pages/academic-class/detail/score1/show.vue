<!-- កាត់ដេរ -->
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
          <VCard :title="`${$t('class')} ${model.name} ${$t('academic_year')} ${model.academic_year?.name}`">
            <VDivider />
            <v-btn
              class="mt-5 mx-5"
              color="secondary"
              variant="outlined"
              @click="$router.go(-1)"
              ><v-icon>mdi-arrow-back</v-icon>&nbsp; {{ $t('back') }}</v-btn
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
                        <VCol style="margin: 0 85%"
                          ><v-img
                            src="/src/assets/images/logo.png"
                            :width="100"
                          ></v-img>
                        </VCol>
                      </VRow>
                    </td>

                    <td colspan="12"></td>
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
                    <td colspan="6"></td>
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
                    <td colspan="6"></td>
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
                    
                    </td>
                    <td
                      colspan="10"
                      style="
                        text-align: center;
                        font-weight: bold;
                        line-height: normal;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      {{ model.academic_year?.name }}
                    </td>
                  </tr>

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
                      colspan="6"
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                    >
                      Room : {{ model.room?.room }}
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
                      Level &nbsp;&nbsp;&nbsp;: {{ model.level?.level }}
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
                      Time &nbsp;: {{ model.time?.time }}
                    </td>
                  </tr>
                  <tr style="font-family: 'Times New Roman', Times, serif;font-size: 17px;">
                    
                    <th
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px;"
                    >
                      N<sup>o</sup>
                    </th>
                    <th
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                      colspan="8"
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
                    <th
                      colspan="2"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Final
                    </th>
                    <th
                      colspan="2"
                      rowspan="2"
                      style="border: 1px solid black; padding: 5px"
                    >
                      Averange
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
                      Grade
                    </th>
                  </tr>
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
                      colspan="8"
                      style="border: 1px solid black; padding: 5px"
                    >
                      {{ ret.last_name + ' ' + ret.first_name }}
                    </td>
                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                    >
                      {{ ret.gender == 1 ? 'ប្រុស' : 'ស្រី' }}
                    </td>
                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                    >
                      {{ ret.final }}
                    </td>
                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                    >
                      {{ ret.final / 10 }}
                    </td>

                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                    >
                      {{ ret.rank }}
                    </td>

                    <td
                      style="text-align: center; border: 1px solid black; padding: 5px"
                      colspan="2"
                    >
                      {{ GradePlus(ret.final) }}
                    </td>
                  </tr>
                  <td
                    style="height: 45px"
                    
                  ></td>
                  <tr>
                    <td
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                      colspan="3"
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
                      colspan="3"
                    >
                      Seen and approved
                    </td>
                    <td colspan="10"></td>
                    <td
                      style="
                        text-align: center;
                        font-weight: bold;
                        font-size: 16px;
                        font-family: 'Times New Roman', Times, serif;
                      "
                      colspan="15"
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
                      colspan="3"
                    >
                      Director
                    </td>
                    <td
                      style="text-align: center"
                      colspan="10"
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
                      colspan="14"
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
                      {{ model.teacher?.name }}
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
