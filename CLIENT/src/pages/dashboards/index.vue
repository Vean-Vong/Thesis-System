<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/plugins/utilites'
const router = useRouter()
onMounted(() => {
  api.post('summary').then(response => {
    statistics.value.forEach(element => {
      element.stats = response.data[element.name]
    })
  })
})
const statistics = ref([
  {
    title: 'Class Total',
    stats: 0,
    icon: 'mdi-account-group-outline',
    color: 'primary',
    name: 'academic_classes',
    to: '/academic-class',
    i18nKey: 'total_academic_classes',
  },
  {
    title: 'Teacher Total',
    stats: 0,
    icon: 'mdi-account-tie',
    color: 'info',
    name: 'teachers',
    to: '/teacher',
    i18nKey: 'teachers_total',
  },
  {
    title: 'Student Total',
    stats: 0,
    icon: 'mdi-account-clock',
    color: 'success',
    name: 'students',
    to: '/student',
    i18nKey: 'total_students',
  },
  {
    title: 'User Total',
    stats: 0,
    icon: 'mdi-account-star',
    color: 'warning',
    name: 'users',
    to: '/user',
    i18nKey: 'total_users',
  },
])

const go = to => {
  router.push(to)
}
</script>

<template>
  <VCard>
    <VCardItem>
      <VCardTitle class="text-xl text-primary"> {{ $t('total_data_table') }}: </VCardTitle>
    </VCardItem>

    <VCardText>
      <VRow>
        <VCol
          v-for="item in statistics"
          :key="item.title"
          cols="12"
          sm="6"
          md="3"
         
        >
        <VCard

          :class="stat-card"
          :color="item.color"

            class="d-flex align-center mb-4 py-4 rounded-2 "
            dark
             elevation="20"
            @click="go(item.to)"
          >
          
            <VAvatar
              :color="item.color"
              rounded
              size="80"
              class="elevation-1 me-5 ms-5"
              
            >
            
            
              <VIcon
                size="50"
                :icon="item.icon"
                
                
              />
            </VAvatar>
            <v-divider vertical class="text-white " :thickness="3"></v-divider>
            <div class="d-flex flex-column mx-5">
              <div class="text-lg" >{{ $t(item.i18nKey) }}</div>
            </div>
            
            <span class="text-h2 font-weight-medium text-white px-5 mx-2">{{ item.stats }}</span>

          </VCArd>
          
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>


<style scoped>
.link:hover {
  cursor: pointer;

}
.stat-card {
  background: linear-gradient(to bottom right, #42a5f5, #0d47a1); /* Gradient background */
  /* Add any additional styling here */
}
</style>
<route lang="yaml">
  meta:
    title: Dashboard
    layout: default
    subject: Auth
    active: 'dashboard'
  </route>