<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/utilites'

const route = useRoute()

const student = ref({})

let getData = () => {
  console.table(route.query.id)
  if (route.query.id) {
    api.post(`students-show`, { id: route.query.id }).then(res => {
      console.log(res.data.model)
      student.value = res.data?.model
      console.table(student)
    })
  }
}

onMounted(() => {
  getData()
})
</script>
<template>
  <!-- <h1>{{ route.query.id }}</h1> -->
  <div>
    <v-card>
      <v-card-title> Student List </v-card-title>
    </v-card>
    <v-card class="mt-7">
      <v-btn
        class="mt-4 mx-5"
        color="secondary"
        variant="outlined"
        @click="$router.go(-1)"
        ><v-icon>mdi-arrow-back</v-icon>&nbsp;{{ $t('back') }}</v-btn
      >
      <v-row>
        <v-col
          col="12"
          md="3"
          class="mt-8 ml-12"
        >
          <v-img
            alt="student"
            src="https://cdn-icons-png.flaticon.com/512/1154/1154987.png"
            width="70%"
          >
          </v-img>
        </v-col>
        <v-col>
          <v-col>
            <v-card-title class="bg-primary rounded"> Profile </v-card-title>
          </v-col>
          <v-row>
            <v-col>
              <v-card-text>Code : {{ student.code }}</v-card-text>
              <v-card-text>Name : {{ student.first_name + ' ' + student.last_name }}</v-card-text>
              <v-card-text>Day Of Birth : {{ student.dob }}</v-card-text>
              <v-card-text>Gender : {{ student.gender }}</v-card-text>
              <v-card-text>Student Status : {{ student.student_status }}</v-card-text>
              <v-card-text>Status : {{ student.status }}</v-card-text>
              <v-card-text>From : {{student.from }}</v-card-text>
              <v-card-text>Phone Number : {{student.phone}}</v-card-text>
              <v-card-text>Other : {{student.other}}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row class="mx-2">
        <v-col>
          <v-card-title class="bg-primary rounded">Place Of Birth</v-card-title>
          <v-row>
            <v-col>
              <v-card-text>Village : {{ student.village_birth }} </v-card-text>
              <v-card-text>Commune : {{ student.commune_birth }} </v-card-text>
              <v-card-text>District : {{ student.district_birth }}</v-card-text>
              <v-card-text>Province : {{ student.province_birth }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
        <v-col>
          <v-card-title class="bg-primary rounded">Address</v-card-title>
          <v-row>
            <v-col>
              <v-card-text>Village : {{ student.village }}</v-card-text>
              <v-card-text>Commune : {{ student.commune }}</v-card-text>
              <v-card-text>District : {{ student.district }}</v-card-text>
              <v-card-text>Province : {{ student.province}}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row class="mx-2">
        <v-col col="12">
          <v-card-title class="bg-primary rounded">Father's Info</v-card-title>
          <v-row>
            <v-col>
              <v-card-text>Name : {{ student.d_first_name + ' ' + student.last_name }}</v-card-text>
              <v-card-text>Job : {{ student.d_job }}</v-card-text>
              <v-card-text>Phone Number : {{ student.d_phone_number }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
        <v-col>
          <v-card-title class="bg-primary rounded">Mother's Info</v-card-title>
          <v-row>
            <v-col>
              <v-card-text>Name : {{ student.m_first_name + ' ' + student.m_last_name }}</v-card-text>
              <v-card-text>Job : {{ student.m_job }}</v-card-text>
              <v-card-text>Phone Number : {{ student.m_phone_number }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row class="mx-2">
        <v-col>
          <v-card-title class="bg-primary rounded">Guardian</v-card-title>
          <v-row>
            <v-col>
              <v-card-text>Name : {{ student.g_first_name + ' ' + student.g_last_name }}</v-card-text>
              <v-card-text>Gender : {{ student.g_gender }}</v-card-text>
              <v-card-text>Detail : {{ student.g_detail }}</v-card-text>
              <v-card-text>Phone Number : {{ student.g_phone_number }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>
