<template>
  <VCard :flat="flat">
    <VCardTitle>
      {{ $t(title) }}
    </VCardTitle>
    <VDivider v-if="!flat" />
    <VCardText class="text-center">
      <label :for="id">
        <VImg
          v-if="imagePreview || placeholder"
          :src="imagePreview || placeholder"
          width="300px"
          height="300px"
          :aspect-ratio="1"
          contain
          class="sakal-image-picker text-center"
        />
        <VImg
          v-else
          src="/placeholder.jpeg"
          width="300px"
          height="300px"
          :aspect-ratio="1"
          contain
          class="sakal-image-picker text-center"
        />
      </label>
      <VFileInput
        :id="id"
        v-model="image"
        accept="image/*"
        style="display: none !important;"
        :name="id"
        :multiple="false"
        @change="handleFileChanged"
      />
    </VCardText>
  </VCard>
</template>

<script setup>
import { ref } from "vue"

const props = defineProps({
  modelValue: File,
  placeholder: String,
  id: {
    type: String,
    default: "sakalImage",
  },
  title: {
    type: String,
    default: "Image",
  },
  flat: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(["update:modelValue"])

const image = ref()
const imagePreview = ref(null)

const handleFileChanged = e => {
  emit("update:modelValue", image.value[0])
  imagePreview.value = URL.createObjectURL(image.value[0])
}
</script>

<style scoped>
.sakal-image-picker {
  cursor: pointer;
}

.sakal-image-picker:hover {
  opacity: 0.8;
}
</style>
