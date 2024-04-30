<template>
  <VDialog :width="500" v-model="dialog">
    <VCard>
      <VCardTitle class="mx-2 my-4 text-center text-h4 font-weight-bold">{{
        $t("Warning")
      }}</VCardTitle>
      <VDivider />
      <VCardText> {{ $t("Do you want to delete?") }} </VCardText>
      <VCardActions>
        <VSpacer></VSpacer>
        <VBtn variant="tonal" color="dark" @click="cancelCallBack">
          <template #prepend>
            <VIcon icon="mdi-cancel"></VIcon>
          </template>
          {{ $t("Cancel") }}
        </VBtn>
        <VBtn
          variant="elevated"
          color="error"
          v-if="props.confirmText"
          @click="confirmDeleteCallback"
          :loading="deleting"
        >
          <template #prepend>
            <VIcon icon="mdi-trash"></VIcon>
          </template>
          {{ $t("Confirm") }}
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>
<script setup>
const props = defineProps({
  width: {
    type: Number,
    default: 500,
  },
  dialog: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: "Are You Sure",
  },
  message: {
    type: String,
    default: "Do you want to delete",
  },
  cancelText: {
    type: String,
    default: "Cancel",
  },
  confirmText: {
    type: String,
    default: "Confirm",
  },
  deleting: {
    type: Boolean,
    default: false,
  },
});
const dialog = ref(props.dialog);

const emit = defineEmits(["onConfirmDelete", "onCancel"]);

const confirmDeleteCallback = (item) => {
  emit("onConfirmDelete", item);
};

const cancelCallBack = () => {
  emit("onCancel");
};
</script>
