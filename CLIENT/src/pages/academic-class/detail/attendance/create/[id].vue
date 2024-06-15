<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/plugins/utilites'
const { params } = useRoute()
const params_id = ref(null)
const params_month = ref(null)
const params_s = ref(null)
const router = useRouter()
const is_fetch = ref(false)
const model = ref({})
const exam_month = ref({})
const form = reactive({
  academic_class_id: params_id.value,
  month: params_month.value,
  total_day: 31,
  attendances: [
    {
      id: null,
      student_id: null,
      name: null,
      sex: null,
      absent: null,
    },
  ],
})
const submitting = ref(false)
const refForm = ref()

const fetchData = () => {
  api
    .post('academic-classes-detail', {
      id: params_id.value,
    })
    .then(res => {
      model.value = res.data.model
      is_fetch.value = true
    })
  fetchForm()
}

const fetchForm = () => {
  api
    .post('attendances-form', {
      academic_class_id: params_id.value,
      month: params_month.value,
    })
    .then(res => {
      Object.assign(form, res.data.form)
    })
}

const submit = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('attendances-save', form)
      .then(res => {
        fetchForm()
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

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

onMounted(() => {
  ;[params_id.value, params_month.value, params_s.value] = params.id.split('_')
  months.value.filter(e => {
    if (e.id == params_month.value) {
      exam_month.value = e
    }
  })
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
          <VCard :title="`ថ្នាក់ទី ${model?.name} ឆ្នាំសិក្សា ${model?.academic_year?.name}`">
            <VDivider />
            <v-btn
              class="mt-5 mx-5"
              color="secondary"
              variant="outlined"
              @click="$router.go(-1)"
              ><v-icon>mdi-arrow-back</v-icon>&nbsp;ថយក្រោយ</v-btn
            >
            <VCardText>
              <v-row>
                <v-col
                  cols="12"
                  md="4"
                  lg="4"
                  sm="12"
                >
                  <div class="text-h6 font-weight-bold">
                    តារាងដាក់វត្តមានប្រចាំ{{ exam_month.id != 0 ? 'ខែ' : '' }}{{ exam_month.name }}
                  </div>
                  <div class="mt-5">
                    <span class="font-weight-bold text-subtitle-1">សម្គាល់៖</span> <br />
                    <span class="ml-2 text-subtitle-2"
                      >1- បញ្ចូលអក្សរ <span class="text-warning">P</span> បើសិស្ស អវត្តមាន (មានច្បាប់)</span
                    >
                    <br />
                    <span class="ml-2 text-subtitle-2"
                      >2- បញ្ចូលអក្សរ <span class="text-primary">A</span> បើសិស្ស អវត្តមាន (គ្មានច្បាប់)</span
                    >
                  </div>
                </v-col>
                <v-col
                  cols="12"
                  md="2"
                  lg="2"
                  sm="12"
                  class="py-0"
                ></v-col>
                <v-col
                  cols="10"
                  md="4"
                  lg="4"
                  sm="10"
                  class="py-0"
                >
                </v-col>
                <v-col
                  cols="2"
                  md="2"
                  lg="2"
                  sm="2"
                  class="mt-1"
                  align="end"
                >
                  <v-btn
                    color="success"
                    type="submit"
                    :loading="submitting"
                    >រក្សាទុក</v-btn
                  >
                </v-col>
              </v-row>
              <table
                style="width: 100%; display: block; overflow-x: auto; white-space: nowrap"
                class="mt-5 pa-0"
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
                    <th colspan="2">ល.រ</th>
                    <th :colspan="form?.attendances[0]?.student?.days?.length > 28 ? '15' : '18'">ឈ្មោះ</th>
                    <th colspan="4">ភេទ</th>
                    <th
                      v-for="date in form.total_day"
                      :key="date"
                    >
                      {{ date.toString().length == '1' ? '0' + date : date }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(student, index) in form.attendances"
                    :key="index"
                  >
                    <td colspan="2" class="text-center">
                      {{ student.number }}
                    </td>
                    <td
                      :colspan="student?.days?.length > 28 ? '15' : '18'"
                      class="text-left"
                    >
                      {{ index }}
                    </td>
                    <td
                      colspan="4"
                      class="text-center"
                    >
                      {{ student.gender == 1 ? 'ប្រុស' : 'ស្រី' }}
                    </td>
                    <td
                      v-for="date in student.days"
                      :key="date"
                    >
                      <!-- <select
                        v-model="student.absent"
                        style="
                          width: 38px;
                          border: 1px black solid;
                          border-radius: 3px;
                          text-align: center;
                        "
                      >
                        <option value=""></option>
                        <option value="A" :selected="student.absent == 'A'">A</option>
                        <option :selected="student.absent == 'P'" value="P">P</option>
                      </select> -->
                      <input
                        v-model="date.absent"
                        style="
                          width: 18px;
                          font-size: 12px;
                          text-align: center;
                          border: 1px black solid;
                          border-radius: 5px;
                        "
                        :class="date.absent == 'P' ? 'text-warning' : 'text-primary'"
                      />
                    </td>
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
  title: Attendance Create
  layout: default
  subject: Auth
  active: 'academic-class'
</route>
<style scoped lang="scss">
table {
  border-collapse: collapse;
}
table,
th,
td {
  border: 1px solid black;
  padding: 5px;
}
</style>
