<script setup>
import api from "@/plugins/utilites";

const provinces = ref([]);
const districts = ref([]);
const communes = ref([]);
const villages = ref([]);

const province = ref({});
const district = ref({});
const commune = ref({});
const village = ref({});

const dialog = ref(false);

const props = defineProps({
  data: {
    type: Object,
    default: () => ({
      provinces: null,
      districts: null,
      communes: null,
      villages: null,
    }),
  },
  dialog: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["getAddress"]);
onMounted(async () => {
  await getProvinces();
});

const apiRequestForLocation = async (form) => {
  return await api.post("/addresses-handle", form).then((res) => res.data.data);
};

const getProvinces = () => {
  api.post("/provinces-list").then((res) => {
    provinces.value = res.data.provinces;
  });
};

const getDistricts = async (it) => {
  if (!province.value) {
    province.value = it;

    districts.value = await apiRequestForLocation({
      province_id: province.value.id,
    });
  } else {
    if (it.id != province.value.id) {
      province.value = it;
      communes.value = [];
      villages.value = [];

      districts.value = await apiRequestForLocation({
        province_id: province.value.id,
      });
    } else {
      districts.value = await apiRequestForLocation({
        province_id: province.value.id,
      });
    }
  }
};

const getCommunes = async (it) => {
  if (!district.value) {
    district.value = it;

    communes.value = await apiRequestForLocation({
      district_id: district.value.id,
    });
  } else {
    if (it.id != district.value.id) {
      district.value = it;
      villages.value = [];

      communes.value = await apiRequestForLocation({
        district_id: district.value.id,
      });
    } else {
      communes.value = await apiRequestForLocation({
        district_id: district.value.id,
      });
    }
  }
};

const getVillages = async (it) => {
  if (!commune.value) {
    commune.value = it;

    villages.value = await apiRequestForLocation({
      commune_id: commune.value.id,
    });
  } else {
    if (it.id != commune.value.id) {
      commune.value = it;

      villages.value = await apiRequestForLocation({
        commune_id: commune.value.id,
      });
    } else {
      villages.value = await apiRequestForLocation({
        commune_id: commune.value.id,
      });
    }
  }
};

const getAddressFull = (it) => {
  api.post("get-address-full", { village_id: it }).then((res) => {
    if (res.status == 200) {
      emit("getAddress", {
        village_id: it,
        address: res.data.data.address,
      });
    }
  });
};
</script>
<template>
  <VDialog v-model="dialog">
    <template v-slot:default="{ isActive }">
      <VCard class="w-100">
        <VCardTitle class="d-flex align-center justify-space-between">
          <div>ការកំណត់អាស័យដ្ឋាន</div>
          <VIcon @click="isActive.value = false">mdi-close</VIcon>
        </VCardTitle>
        <v-divider class="mb-5" />
        <VCardActions>
          <VRow class="pr-0" dense>
            <VCol cols="12" md="3" sm="6">
              <p class="ml-2 font-weight-bold text-lg">រាជធានី / ខេត្ត</p>
              <div
                v-for="(it, idx) in provinces"
                v-if="provinces"
                :key="idx"
                class="d-flex justify-sm-space-between justify-center w-100"
                style="align-items: center"
              >
                <VBtn
                  variant="tonal"
                  :color="province.id == it.id ? 'primary' : 'secondary'"
                  class="mb-0 mt-2 w-100 d-flex justify-space-between"
                  @click.prevent="getDistricts(it)"
                  >{{ it.fullname }}
                  <template #append>
                    <div class="me-4">
                      {{ it.id }}
                    </div>
                  </template>
                </VBtn>
              </div>
            </VCol>
            <VCol v-if="districts.length > 0" cols="12" md="3" sm="6">
              <p class="ml-2 font-weight-bold text-lg">ស្រុក / ក្រុង / ខណ្ឌ</p>
              <div
                v-for="(it, idx) in districts"
                v-if="districts"
                :key="idx"
                class="d-flex justify-content-between w-100"
                style="align-items: center"
              >
                <VBtn
                  variant="tonal"
                  :color="district.id == it.id ? 'primary' : 'secondary'"
                  class="mt-2 w-100 d-flex justify-space-between"
                  block
                  @click.prevent="getCommunes(it)"
                >
                  {{ it.fullname }}
                  <template #append>
                    <div class="me-4">
                      {{ it.id }}
                    </div>
                  </template>
                </VBtn>
              </div>
            </VCol>
            <VCol v-if="communes.length > 0" cols="12" md="3" sm="6">
              <p class="ml-2 font-weight-bold text-lg">ឃុំ / សង្កាត់</p>
              <div
                v-for="(it, idx) in communes"
                v-if="communes"
                :key="idx"
                class="d-flex justify-content-between w-100"
                style="align-items: center"
              >
                <VBtn
                  variant="tonal"
                  :color="commune.id == it.id ? 'primary' : 'secondary'"
                  class="mb-0 mt-2 w-100 d-flex justify-space-between"
                  block
                  @click.prevent="getVillages(it)"
                  >{{ it.fullname }}
                  <template #append>
                    <div class="me-4">
                      {{ it.id }}
                    </div>
                  </template>
                </VBtn>
              </div>
            </VCol>
            <VCol v-if="villages.length > 0" cols="12" md="3" sm="6">
              <p class="ml-2 font-weight-bold text-lg">ភូមិ</p>
              <div
                v-for="(it, idx) in villages"
                v-if="villages"
                :key="idx"
                class="d-flex justify-content-between w-100"
                style="align-items: center"
              >
                <VBtn
                  variant="tonal"
                  :color="village == it.id ? 'primary' : 'secondary'"
                  class="mb-0 mt-2 w-100 d-flex justify-space-between"
                  block
                  @click.prevent="getAddressFull(it.id), (isActive.value = false)"
                  >{{ it.fullname }}
                  <template #append>
                    <div class="me-4">
                      {{ it.id }}
                    </div>
                  </template>
                </VBtn>
              </div>
            </VCol>
          </VRow>
        </VCardActions>
      </VCard>
    </template>
  </VDialog>
</template>

<route lang="yaml">
meta:
  title: Address Setting
  layout: default
  subject: Auth
</route>
