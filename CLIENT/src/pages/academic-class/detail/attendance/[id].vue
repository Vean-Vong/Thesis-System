<script setup>
import { onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/plugins/utilites"
const { params } = useRoute();
const router = useRouter();
const params_id = ref(null);
const params_create = ref(null);
const model = ref({});

const fetchData = () => {
  api
    .post("academic-classes-detail", {
      id: params_id.value,
    })
    .then((res) => {
      model.value = res.data.model;
    });
};

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
]);

const study_months = ref([]);

const onSelectMonth = (id, s) => {
  if (params_create.value) {
    router.push(
      `/academic-class/detail/attendance/show/${params_id.value}_${id}${
        s != undefined ? "_" + s : ""
      }`
    );
  } else {
    router.push(`/academic-class/detail/attendance/create/${params_id.value}_${id}`);
  }
};

const fetchMonth = () => {
  api
    .post("academic-classes-month", {
      id: params_id.value,
    })
    .then((res) => {
      study_months.value = res.data;
    });
};

onMounted(() => {
  [params_id.value, params_create.value] = params.id.split("_");
  fetchData();
  fetchMonth();
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
            <div class="text-h6 font-weight-bold">
              ជ្រើសរើសខែសម្រាប់{{ params_create ? "បង្ហាញ" : "ដាក់" }}វត្តមាន
            </div>
            <v-row class="mt-5">
              <v-col
                cols="12"
                md="6"
                lg="6"
                sm="6"
                v-for="month in study_months"
                :key="month"
              >
                <v-card @click="onSelectMonth(month.id)">
                  <v-card-title class="text-center">{{ month.name }}</v-card-title>
                </v-card>
              </v-col>
            </v-row>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>
