<script setup>
import api from "@/plugins/utilites";

const props = defineProps({
  village: {
    type: Object,
    default: () => ({
      id: null,
      namekh: null,
      nameen: null,
      type: null,
      reference: null,
      status: 1,
      shortcut: null,

      commune_id: null,
    }),
  },
  villageVisibleDialog: {
    type: Boolean,
    required: true,
  },
  id: {
    type: Number,
  },
  communeID: {
    type: Number,
  },
});

const emit = defineEmits(["update:villageVisibleDialog", "submit"]);

const villageType = [
  {
    name: "ភូមិ",
  },
];

const village = ref(props.village);

const getVillageByID = (id) => {
  api
    .post("/villages-show", { id: id })
    .then((res) => {
      village.value = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });
};

watch(
  () => props.villageVisibleDialog,
  (newVal, oldVal) => {
    if (newVal && props.id != null) {
      getVillageByID(props.id);
    } else {
      village.value = props.village;
    }
  }
);

const resetForm = () => {
  emit("update:villageVisibleDialog", false);

  village.value = structuredClone(toRaw(props.village));
};

const onFormSubmit = () => {
  village.value.commune_id = props.communeID;

  if (props.id) {
    api
      .post("/villages-update", village.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  } else {
    api
      .post("/villages-store", village.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  }

  emit("update:villageVisibleDialog", false);
  emit("submit", village.value);

  village.value = structuredClone(toRaw(props.village));
};
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    :model-value="props.villageVisibleDialog"
    @update:model-value="(val) => $emit('update:villageVisibleDialog', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:villageVisibleDialog', false)" />

    <VCard v-if="props.villageVisibleDialog" class="pa-sm-8 pa-5">
      <!-- 👉 Title -->
      <VCardItem>
        <VCardTitle class="text-h4 text-center">
          {{ props.id ? "Edit" : "Add New" }} Village
        </VCardTitle>
      </VCardItem>

      <VCardText>
        <!-- 👉 Form -->
        <VForm class="mt-4" @submit.prevent="onFormSubmit">
          <VRow>
            <VCol cols="12" md="6">
              <AppTextField v-model="village.id" label="Village Code" />
            </VCol>
            <VCol cols="12" md="6">
              <label for="type">Village Type</label>
              <VAutocomplete
                id="type"
                v-model="village.type"
                :items="villageType"
                item-value="name"
                item-title="name"
                value="ភូមិ"
                persistent-placeholder
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="village.namekh"
                label="Village Name Khmer"
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="village.nameen"
                label="Village Name English"
              />
            </VCol>

            <VCol cols="12" md="6">
              <AppTextField
                v-model="village.shortcut"
                label="Village Shortcut"
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="village.reference"
                label="Village Reference"
              />
            </VCol>

            <VCol cols="12">
              <v-checkbox
                label="Active"
                v-model="village.status"
                :value="village.status"
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
