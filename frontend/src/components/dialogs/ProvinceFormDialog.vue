<script setup>
import api from "@/plugins/utilites";

const props = defineProps({
  province: {
    type: Object,
    default: () => ({
      id: null,
      namekh: null,
      nameen: null,
      type: null,
      shortcut: null,
      reference: null,
      status: 1,
    }),
  },
  provinceVisibleDialog: {
    type: Boolean,
    required: true,
  },
  id: {
    type: Number,
    required: false,
  },
});

const provinceType = [
  {
    name: "រាវធានី",
  },
  {
    name: "ខេត្ត",
  },
];

const emit = defineEmits(["update:provinceVisibleDialog", "submit"]);

const province = ref(props.province);

const getProvinceByID = (id) => {
  api
    .post("/provinces-show", { id: id })
    .then((res) => {
      province.value = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });
};

watch(
  () => props.provinceVisibleDialog,
  (newVal, oldVal) => {
    if (newVal && props.id != null) {
      getProvinceByID(props.id);
    } else {
      province.value = structuredClone(toRaw(props.province));
    }
  }
);

const resetForm = () => {
  emit("update:provinceVisibleDialog", false);

  province.value = structuredClone(toRaw(props.province));
};

const onFormSubmit = async () => {
  if (props.id) {
    await api
      .post("/provinces-update", province.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  } else {
    await api
      .post("/provinces-store", province.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  }

  province.value = structuredClone(toRaw(props.province));

  emit("update:provinceVisibleDialog", false);
  emit("submit", province.value);
};
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    :model-value="props.provinceVisibleDialog"
    @update:model-value="(val) => $emit('update:provinceVisibleDialog', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:provinceVisibleDialog', false)" />

    <VCard v-if="props.provinceVisibleDialog" class="pa-sm-8 pa-5">
      <!-- 👉 Title -->
      <VCardItem>
        <VCardTitle class="text-h4 text-center">
          {{ id ? "Edit" : "Add New" }} Province
        </VCardTitle>
      </VCardItem>

      <VCardText>
        <!-- 👉 Form -->
        <VForm class="mt-4" @submit.prevent="onFormSubmit">
          <VRow>
            <VCol cols="12" md="6">
              <AppTextField v-model="province.id" label="Province Code" />
            </VCol>
            <VCol cols="12" md="6">
              <label for="type">Province Type</label>
              <VAutocomplete
                id="type"
                v-model="province.type"
                :items="provinceType"
                item-value="name"
                item-title="name"
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="province.namekh"
                label="Province Name Khmer"
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="province.nameen"
                label="Province Name Latin"
              />
            </VCol>

            <VCol cols="12" md="6">
              <AppTextField
                v-model="province.shortcut"
                label="Province Shortcut"
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="province.reference"
                label="Province Reference"
              />
            </VCol>
            <VCol cols="12">
              <v-checkbox
                label="Active"
                v-model="province.status"
                :value="province.status"
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
