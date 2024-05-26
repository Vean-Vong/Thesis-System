<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/plugins/utilites"
const { params } = useRoute();
const params_id = ref(null);
const params_month = ref(null);
const params_s = ref(null);
const router = useRouter();
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
      dob: null,
      sex: null,
      k: null,
      m: null,
      sc: null,
      s: null,
    },
  ],
});
const submitting = ref(false);
const refForm = ref();

const fetchData = () => {
  api
    .post("academic-classes-detail", {
      id: params_id.value,
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
      academic_class_id: params_id.value,
      type: params_month.value,
      semester: form.semester,
    })
    .then((res) => {
      Object.assign(form, res.data.form);
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

const onSelectMonth = (id) => {
  router.push("/academic-class/detail/score/create/" + params.id + "-" + id);
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
                    តារាងដាក់ពិន្ទុប្រចាំ{{ exam_month.id != 0 ? "ខែ" : ""
                    }}{{ exam_month.name }}
                  </div>
                </v-col>
                <v-col cols="12" md="2" lg="2" sm="12" class="py-0"></v-col>
                <v-col cols="10" md="4" lg="4" sm="10">
                  <!-- <v-autocomplete
                    v-if="params_month != 0"
                    :items="semesters"
                    item-title="name"
                    label="ឆមាស"
                    item-value="id"
                    v-model="form.semester"
                    :rules="[(v) => !!v || 'ឆមាស តម្រូវអោយបំពេញ']"
                  >
                  </v-autocomplete> -->
                </v-col>
                <v-col cols="2" md="2" lg="2" sm="2" class="mt-1" align="end">
                  <v-btn color="success" type="submit" :loading="submitting"
                    >រក្សាទុក</v-btn
                  >
                </v-col>
              </v-row>
              <table style="width: 100%" class="mt-5">
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
                    <th rowspan="2">ល.រ</th>
                    <th rowspan="2" colspan="3">ឈ្មោះ</th>
                    <th rowspan="2" colspan="3">ថ្ងៃខែឆ្នាំកំណើត</th>
                    <th rowspan="2">ភេទ</th>
                    <th colspan="12">ពិន្ទុ</th>
                  </tr>
                  <tr>
                    <th colspan="3">ភាសាខ្មែរ</th>
                    <th colspan="3">គណិតវិទ្យា</th>
                    <th colspan="3">វិទ្យាសាស្រ្ត</th>
                    <th colspan="3">សិក្សាសង្គម</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(exam, index) in form.exams" :key="index">
                    <td class="text-center">{{ index + 1 }}</td>
                    <td colspan="3">{{ exam.name }}</td>
                    <td colspan="3">{{ exam.dob }}</td>
                    <td class="text-center">{{ exam.sex == 1 ? "ប្រុស" : "ស្រី" }}</td>
                    <td colspan="3">
                      <v-text-field
                        variant="outlined"
                        density="compact"
                        class="px-4 py-2"
                        v-model="exam.k"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td colspan="3">
                      <v-text-field
                        variant="outlined"
                        density="compact"
                        class="px-4 py-2"
                        v-model="exam.m"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td colspan="3">
                      <v-text-field
                        variant="outlined"
                        density="compact"
                        class="px-4 py-2"
                        v-model="exam.sc"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
                      />
                    </td>
                    <td colspan="3">
                      <v-text-field
                        variant="outlined"
                        density="compact"
                        class="px-4 py-2"
                        v-model="exam.s"
                        :rules="[(v) => v <= 10 || 'ពិន្ទុអតិបរមា១០']"
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
</style>
