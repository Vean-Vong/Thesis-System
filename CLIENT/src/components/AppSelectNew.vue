<script setup>
defineOptions({
  name: 'AppSelect',
  inheritAttrs: false,
})

const elementId = computed(() => {
  const attrs = useAttrs()
  const _elementIdToken = attrs.id || attrs.label

  return _elementIdToken ? `app-select-${_elementIdToken}-${Math.random().toString(36).slice(2, 7)}` : undefined
})

const label = computed(() => useAttrs().label)
const required = computed(() => useAttrs().required)
</script>

<template>
  <div
    class="app-select flex-grow-1"
    :class="$attrs.class"
  >
    <VLabel
      v-if="label"
      :for="elementId"
      class="mb-1 text-body-2 text-high-emphasis"
      ><template #default>
        {{ label }}
        <span
          v-if="required"
          class="required"
        >
          *</span
        >
      </template></VLabel
    >
    <VSelect
      v-bind="{
        ...$attrs,
        class: null,
        label: undefined,
        variant: 'outlined',
        id: elementId,
        menuProps: {
          contentClass: [
            'app-inner-list',
            'app-select__content',
            'v-select__content',
            $attrs.multiple !== undefined ? 'v-list-select-multiple' : '',
          ],
        },
      }"
    >
      <template
        v-for="(_, name) in $slots"
        #[name]="slotProps"
      >
        <slot
          :name="name"
          v-bind="slotProps || {}"
        />
      </template>
    </VSelect>
  </div>
</template>

<style scoped>
.required {
  color: red;
  padding-left: 3px;
}
</style>
