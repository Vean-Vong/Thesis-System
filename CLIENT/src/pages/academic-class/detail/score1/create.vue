<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/plugins/utilites"
const { params } = useRoute();
const params_id = ref(null);
const params_month = ref(null);
const params_s = ref(null);
const router = useRouter();
const route = useRoute();
const is_fetch = ref(false);
const model = ref({});
const exam_month = ref({});
const form = reactive({
  academic_class_id: params_id.value,
  type: params_month.value,
  semester: params_s || 1,
  exams: [
    {
      id: null,
      student_id: null,
      name: null,
      sex: null,
      att: 0,
      quiz: 0,
      hw: 0,
      re: 0,
      voc: 0,
      gr: 0,
      liu: 0,
      wr: 0,
      li: 0,
      sp: 0,
      mid: 0,
      attitude: 0,
      final: 0,
      avg:0,
    },
  ],
});
const submitting = ref(false);
const refForm = ref();

const fetchData = () => {
  api
    .post("academic-classes-detail", {
      id: route.query.id,
    })
    .then((res) => {
      model.value = res.data.model;
      is_fetch.value = true;
    });
  fetchForm();
};

const fetchForm = () => {
  api
    .post("exam-form", {
      academic_class_id: route.query.id
    })
    .then((res) => {
      Object.assign(form.exams, res.data.form);
    });
};

const submit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("exam-save", form)
      .then((res) => {
        fetchForm();
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};


watch(
  () => form.semester,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (is_fetch.value) {
        if (form.type == 0) {
          fetchData();
        }
      }
    }
  }
);

onMounted(() => {
  form.academic_class_id = route.query.id;
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
              <v-row>
                <v-col cols="12" md="4" lg="4" sm="12">
                  <div class="text-h6 font-weight-bold">
                    តារាងដាក់ពិន្ទុ​​   កាត់ដេរ
                  </div>
                </v-col>
                <v-col cols="12" md="2" lg="2" sm="12" class="py-0"></v-col>
                <v-col cols="10" md="4" lg="4" sm="10">
                </v-col>
                <v-col cols="2" md="2" lg="2" sm="2" class="mt-1" align="end">
                  <v-btn color="success" type="submit" :loading="submitting"
                    >រក្សាទុក</v-btn
                  >
                </v-col>
              </v-row>
              <table style="width: 60%" class="mt-5">
                <thead>
                  <tr>
                    <th rowspan="2" colspan="1" >ល.រ</th>
                    <th rowspan="2" colspan="3">ឈ្មោះ</th>
                    <th rowspan="1" colspan="1">ភេទ</th>
                    <th colspan="3">Final</th>
                  </tr>
                  <!-- <tr>
                    <th>Att<br>10%</th>
                    <th>Quiz<br>10%</th>
                    <th>HW<br>10%</th>
                    <th>Re<br>10%</th>
                    <th>Voc.<br>10%</th>
                    <th>Gr.<br>10%</th>
                    <th>LIU<br>10%</th>
                    <th>Wr<br>10%</th>
                    <th>Li<br>10%</th>
                    <th>Sp.<br>10%</th>
                  </tr> -->
                </thead>
                <tbody>
                  <tr v-for="(exam, index) in form.exams" :key="index">
                    <td class="text-center">{{ index + 1 }}</td>
                    <td colspan="3">{{ exam.last_name }} {{ exam.first_name }}</td>
                    <td class="text-center">{{ exam.gender == 1 ? "ប្រុស" : "ស្រី" }}</td>
                    <td class="text-center">
                      <v-text-field 
                        class="cus"
                        variant="plain"
                        density="compact"
                        v-model="exam.final"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០០']"
                      />
                    </td>
                    <!-- <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        v-model="exam.quiz"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.hw"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.re"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.voc"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.gr"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.liu"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.wr"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.li"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td>
                      <v-text-field
                        class="cus"
                        variant="plain"
                        density="compact"
                        
                        v-model="exam.sp"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td> -->
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

<style scoped>
table {
  border-collapse: collapse;
}
table,
th,
td {
  border: 1px solid black;
  padding: 5px;
}
.cus >>> input {
    text-align: center
}
</style>
