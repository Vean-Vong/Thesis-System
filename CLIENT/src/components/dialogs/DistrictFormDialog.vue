<script setup>
import api from "@/plugins/utilites";

const props = defineProps({
  district: {
    type: Object,
    default: () => ({
      id: null,
      namekh: null,
      nameen: null,
      type: null,
      reference: null,
      status: 1,
      shortcut: null,

      province_id: null,
    }),
  },
  districtVisibleDialog: {
    type: Boolean,
    required: true,
  },
  id: {
    type: Number,
  },
  provinceID: {
    type: Number,
  },
});

const districtType = [
  {
    name: "ស្រុក",
  },
  {
    name: "ក្រុង",
  },
  {
    name: "ខណ្ឌ",
  },
];

const emit = defineEmits(["update:districtVisibleDialog", "submit"]);

const district = ref(props.district);

const getDistrictByID = (id) => {
  api
    .post("/districts-show", { id: id })
    .then((res) => {
      district.value = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });
};

watch(
  () => props.districtVisibleDialog,
  (newVal, oldVal) => {
    if (newVal && props.id != null) {
      getDistrictByID(props.id);
    } else {
      district.value = props.district;
    }
  }
);

const resetForm = () => {
  emit("update:districtVisibleDialog", false);

  district.value = structuredClone(toRaw(props.district));
};

const onFormSubmit = async () => {
  district.value.province_id = props.provinceID;

  if (props.id) {
    await api
      .post("/districts-update", district.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  } else {
    await api
      .post("/districts-store", district.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  }

  emit("update:districtVisibleDialog", false);
  emit("submit", district.value);

  district.value = structuredClone(toRaw(props.district));
};
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    :model-value="props.districtVisibleDialog"
    @update:model-value="(val) => $emit('update:districtVisibleDialog', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:districtVisibleDialog', false)" />

    <VCard v-if="props.districtVisibleDialog" class="pa-sm-8 pa-5">
      <!-- 👉 Title -->
      <VCardItem>
        <VCardTitle class="text-h4 text-center">
          {{ props.id ? "Edit" : "Add New" }} District
        </VCardTitle>
      </VCardItem>

      <VCardText>
        <!-- 👉 Form -->
        <VForm class="mt-4" @submit.prevent="onFormSubmit">
          <VRow>
            <VCol cols="12" md="6">
              <AppTextField v-model="district.id" label="District Code" />
            </VCol>
            <VCol cols="12" md="6">
              <label for="type">District Type</label>
              <VAutocomplete
                id="type"
                v-model="district.type"
                :items="districtType"
                item-value="name"
                item-title="name"
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="district.namekh"
                label="District Name Khmer"
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="district.nameen"
                label="District Name English"
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="district.shortcut"
                label="District Shortcut"
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="district.reference"
                label="District Reference"
              />
            </VCol>

            <VCol cols="12">
              <v-checkbox
                label="Active"
                v-model="district.status"
                :value="district.status"
              ></v-checkbox>
            </VCol>

            <!-- 👉 Submit and Cancel button -->
            <VCol cols="12" class="text-center">
              <VBtn type="submit" class="me-3">
                {{ id ? "Update" : "Create" }}
              </VBtn>

              <VBtn variant="tonal" color="secondary" @click="resetForm">
                Cancel
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
