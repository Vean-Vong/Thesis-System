<script setup>
import { onMounted, ref } from "vue"
import { useRouter } from "vue-router"
import api from "@/plugins/utilites"
const router = useRouter()
onMounted(() => {
  api.post("summary").then(response => {
    statistics.value.forEach(element => {
      element.stats = response.data[element.name]
    })
  })
})

const statistics = ref([
  {
    title: "ថ្នាក់រៀនសរុប",
    stats: 0,
    icon: "mdi-account-group-outline",
    color: "primary",
    name: "academic_classes",
    to: "/academic-class",
  },
  {
    title: "គ្រូបង្រៀនសរុប",
    stats: 0,
    icon: "mdi-account-tie",
    color: "info",
    name: "teachers",
    to: "/teacher",
  },
  {
    title: "សិស្សសរុប",
    stats: 0,
    icon: "mdi-account-clock",
    color: "success",
    name: "students",
    to: "/student",
  },
  {
    title: "អ្នកប្រើប្រាស់សរុប",
    stats: 0,
    icon: "mdi-account-star",
    color: "warning",
    name: "users",
    to: "/user",
  },
])

const go = to => {
  router.push(to)
}
</script>

<template>
  <VCard>
    <VCardItem>
      <VCardTitle class="text-xl text-info">
        តារាងទិន្នន័យសរុបតាមផ្នែក:
      </VCardTitle>
    </VCardItem>

    <VCardText>
      <VRow>
        <VCol
          v-for="item in statistics"
          :key="item.title"
          cols="6"
          sm="3"
        >
          <div
            class="d-flex align-center link"
            @click="go(item.to)"
          >
            <div class="me-3">
              <VAvatar
                :color="item.color"
                rounded
                size="48"
                class="elevation-1"
              >
                <VIcon
                  size="28"
                  :icon="item.icon"
                />
              </VAvatar>
            </div>

            <div class="d-flex flex-column">
              <span class="text-lg"> {{ item.title }} </span>
              <span class="text-h6 font-weight-medium"><Number
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

<style scoped>
.link:hover {
  cursor: pointer;
}
</style>
