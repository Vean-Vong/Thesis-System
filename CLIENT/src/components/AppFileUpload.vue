<template>
  <VFileInput
    v-model="item"
    :label="label"
    variant="underlined"
  />
</template>

<script setup>
import { computed } from "vue"

const props = defineProps({
  modelValue: Object,
  lable: {
    type: String,
    default: "Source",
  },
})

const emit = defineEmits(['update:modelValue'])

const item = computed({
  get() {
    return props.modelValue
  },
  set(val) {
    emit("update:modelValue", val)
  },
})
</script>
