<script setup>
import api from "@/plugins/utilites";

const props = defineProps({
  commune: {
    type: Object,
    default: () => ({
      id: null,
      namekh: null,
      nameen: null,
      type: null,
      reference: null,
      status: 1,
      shortcut: null,

      district_id: null,
    }),
  },
  communeVisibleDialog: {
    type: Boolean,
    required: true,
  },
  id: {
    type: Number,
  },
  districtID: {
    type: Number,
  },
});

const emit = defineEmits(["update:communeVisibleDialog", "submit"]);

const communeType = [
  {
    name: "ឃុំ",
  },
  {
    name: "សង្កាត់",
  },
];

const commune = ref(props.commune);

const getCommuneByID = (id) => {
  api
    .post("/communes-show", { id: id })
    .then((res) => {
      commune.value = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });
};

watch(
  () => props.communeVisibleDialog,
  (newVal, oldVal) => {
    if (newVal && props.id != null) {
      getCommuneByID(props.id);
    } else {
      commune.value = props.commune;
    }
  }
);

const resetForm = () => {
  emit("update:communeVisibleDialog", false);

  commune.value = structuredClone(toRaw(props.commune));
};

const onFormSubmit = () => {
  commune.value.district_id = props.districtID;

  if (props.id) {
    api
      .post("/communes-update", commune.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  } else {
    api
      .post("/communes-store", commune.value)
      .then((res) => {
        console.log(res.data);
      })
      .catch((err) => {
        console.log(err);
      });
  }

  emit("update:communeVisibleDialog", false);
  emit("submit", commune.value);

  commune.value = structuredClone(toRaw(props.commune));
};
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    :model-value="props.communeVisibleDialog"
    @update:model-value="(val) => $emit('update:communeVisibleDialog', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:communeVisibleDialog', false)" />

    <VCard v-if="props.communeVisibleDialog" class="pa-sm-8 pa-5">
      <!-- 👉 Title -->
      <VCardItem>
        <VCardTitle class="text-h4 text-center">
          {{ props.id ? "Edit" : "Add New" }} Commune
        </VCardTitle>
      </VCardItem>

      <VCardText>
        <!-- 👉 Form -->
        <VForm class="mt-4" @submit.prevent="onFormSubmit">
          <VRow>
            <VCol cols="12" md="6">
              <AppTextField v-model="commune.id" label="Commune Code" />
            </VCol>
            <VCol cols="12" md="6">
              <label for="type">Commune Type</label>
              <VAutocomplete
                id="type"
                v-model="commune.type"
                :items="communeType"
                item-value="name"
                item-title="name"
                persistent-placeholder
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="commune.namekh"
                label="Commune Name Khmer"
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="commune.nameen"
                label="Commune Name English"
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppTextField
                v-model="commune.shortcuts"
                label="Commune Shortcut"
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="commune.reference"
                label="Commune Reference"
              />
            </VCol>
            <VCol cols="12">
              <v-checkbox
                label="Active"
                v-model="commune.status"
                :value="commune.status"
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
