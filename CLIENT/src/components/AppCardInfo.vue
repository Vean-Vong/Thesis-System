<script setup>
const props = defineProps({
  item: {
    type: Object,
    default: {},
  },
});
</script>

<template>
  <VCol cols="6" md="3" v-if="item.type == 'Add'">
    <VCard
      class="pa-0 pa-lg-0 h-100 bg-primary d-flex align-center"
      rounded="0"
      style="cursor: pointer"
    >
      <VRow align="center" class="text-center h-100">
        <VCol cols="4" md="3">
          <VIcon class="text-h1 text-white">mdi-plus</VIcon>
        </VCol>
        <VDivider vertical color="white"></VDivider>
        <VCol cols="8" md="9">
          <VListItem class="text-center text-lg-h4 pa-0 pa-lg-auto">
            {{ $t(item.Name) }}
          </VListItem>
        </VCol>
      </VRow>
    </VCard>
  </VCol>
  <VCol cols="6" md="3" v-else-if="item.type == 'Total'">
    <VCard
      class="pa-3 pa-lg-0 h-auto"
      :class="item.backGroundColor"
      rounded="0"
      style="cursor: pointer"
    >
      <VRow align="center" class="text-center">
        <VCol cols="4" md="3">
          <VIcon class="text-h1 text-white">{{ item.icon }}</VIcon>
        </VCol>
        <VDivider vertical color="white"></VDivider>
        <VCol cols="8" md="9">
          <VListItem class="text-lg-h4 pa-0 pa-lg-auto">{{ $t(item.Name) }}</VListItem>
          <VListItem class="text-h5 text-end">{{ item.count }}</VListItem>
        </VCol>
      </VRow>
    </VCard>
  </VCol>
</template>
