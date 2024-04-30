<script setup>
import { computed, defineProps, useAttrs } from 'vue';

const { modelValue } = defineProps([
  "modelValue",
]);

const elementId = computed(() => {
  const attrs = useAttrs();
  const _elementIdToken = attrs.id || attrs.label;
  return _elementIdToken
    ? `app-text-field-${_elementIdToken}-${Math.random().toString(36).slice(2, 7)}`
    : undefined;
});

const label = computed(() => useAttrs().label);
const required = computed(() => useAttrs().required);



// const formatDate = (date) => {
//   if (date) {
//     const dateString = typeof date === 'string' ? date : date.toISOString().split('T')[0];
//     const [year, month, day] = dateString.split('-');
//     return `${month}/${parseInt(day) + 1}/${year}`;
//   }
//   return null;
// };
const emit = defineEmits("update:modelValue");

const selectedDate = ref(modelValue)
const isMenuOpen = ref(false);
const formattedDate = computed(() => {
  return selectedDate.value ? selectedDate.value.toLocaleDateString("en") : "";
});

watch(modelValue, (newDate) => {
  selectedDate.value = newDate;
});
watch(selectedDate, (newDate) => {
  emit("update:modelValue", newDate);
});
</script>

<template>
  <!-- <VLabel v-if="label" :for="elementId" :class="'mb-1 text-body-2 text-high-emphasis'">
    <template #default>
      {{ label }} <span v-if="required" class="required"> *</span>
    </template>
  </VLabel>
  <VMenu>
    <template #activator="{ props }">
      <VTextField readonly v-bind="props">
        <template #append-inner>
          <VIcon>mdi-calendar-range</VIcon>
        </template>
        {{ date }}
      </VTextField>
    </template>
    <VDatePicker v-model="date"></VDatePicker>
  </VMenu> -->
  <v-menu v-model="isMenuOpen" :close-on-content-click="false">
    <template v-slot:activator="{ props }">
      <v-text-field
        :model-value="formattedDate"
        readonly
        v-bind="props"
        variant="solo"
        hide-details
      ></v-text-field>
    </template>
    <v-date-picker v-model="selectedDate" hide-actions title="" :color="color">
      <template v-slot:header></template>
    </v-date-picker>
  </v-menu>
</template>
