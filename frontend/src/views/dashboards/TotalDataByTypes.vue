<script setup>
import { onMounted, ref } from "vue";
import api from "../../utils/utilites";

onMounted(() => {
  api.post("total-by-type").then((response) => {
    statistics.value.forEach((element) => {
      element.stats = response.data[element.name];
    });
  });
});

const statistics = ref([
  {
    title: "កំណើត",
    stats: 0,
    icon: "mdi-text-box",
    color: "primary",
    name: "birth",
  },
  {
    title: "បញ្ជាក់កំណើត",
    stats: 0,
    icon: "mdi-text-box-check",
    color: "primary",
    name: "birth_copy",
  },
  {
    title: "អាពាពិពាហ៍",
    stats: 0,
    icon: "mdi-notebook-heart-outline",
    color: "info",
    name: "married",
  },
  {
    title: "បញ្ជាក់អាពាពិពាហ៍",
    stats: 0,
    icon: "mdi-notebook-heart",
    color: "info",
    name: "married_copy",
  },
  {
    title: "មរណៈភាព",
    stats: 0,
    icon: "mdi-account-minus",
    color: "warning",
    name: "death",
  },
  {
    title: "បញ្ជាក់មរណៈភាព",
    stats: 0,
    icon: "mdi-account-minus-outline",
    color: "warning",
    name: "death_copy",
  },
]);
</script>

<template>
  <VCard>
    <VCardItem>
      <VCardTitle class="text-xl text-info">ទិន្នន័យអត្រានុកូលដ្ឋានតាមផ្នែក:</VCardTitle>
    </VCardItem>

    <VCardText>
      <VRow>
        <VCol
          v-for="item in statistics"
          :key="item.title"
          cols="6"
          sm="6"
          md="12"
          lg="12"
        >
          <div class="d-flex align-center">
            <div class="me-3">
              <VAvatar :color="item.color" rounded size="48" class="elevation-1">
                <VIcon size="28" :icon="item.icon" />
              </VAvatar>
            </div>

            <div class="d-flex flex-column">
              <span class="text-lg"> {{ item.title }} </span>
              <span class="text-h6 font-weight-medium"
                ><number
                  ref="number1"
                  :from="0"
                  :to="item.stats"
                  :duration="1"
                  easing="Power1.easeOut"
              /></span>
            </div>
          </div>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
