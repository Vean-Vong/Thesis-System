<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/utilites'
import moment from 'moment'
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
const formatDate = date => {
  return moment(date).format('D-MMM-YYYY')
}

onMounted(() => {
  getData()
})
</script>
<template>
  <!-- <h1>{{ route.query.id }}</h1> -->
  <div>
    <v-card>
      <v-card-title> {{ $t('student_list') }} </v-card-title>
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
          <v-row class="align-center mx-4">
            <v-card-title
              class="text-center bg-success rounded-pill w-25"
              style="padding: 2px"
            >
              {{ $t('profile') }}
            </v-card-title>
            <v-card-title
              class="bg-success rounded-pill w-75"
              style="margin-left: -3px; padding: 3px"
            >
            </v-card-title>
          </v-row>
          <v-row>
            <v-col>
              <v-card-text>{{ $t('code') }} : {{ student.code }}</v-card-text>
              <v-card-text>{{ $t('headers.name') }} : {{ student.last_name + ' ' + student.first_name }}</v-card-text>
              <v-card-text>{{ $t('dob') }} : {{ formatDate(student.dob) }}</v-card-text>
              <v-card-text>{{ $t('headers.gender') }} : {{ student.sex_text }}</v-card-text>
              <v-card-text>{{ $t('status') }} : {{ student.student_status }}</v-card-text>
              <v-card-text>{{ $t('from') }} : {{ student.from }}</v-card-text>
              <v-card-text>{{ $t('headers.phone_number') }} : {{ student.phone }}</v-card-text>
              <v-card-text>{{ $t('other') }} : {{ student.other }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row class="mx-2">
        <v-col>
          <v-row class="align-center mx-1">
            <v-card-title
              class="bg-primary rounded-pill w-25 text-center"
              style="padding: 2px"
              >{{ $t('pob') }}</v-card-title
            >
            <v-card-title
              class="bg-primary rounded-pill w-75"
              style="margin-left: -3px; font-size: 12px; padding: 3px"
            >
            </v-card-title>
          </v-row>
          <v-row>
            <v-col>
              <v-card-text>{{ $t('village') }} : {{ student.village_birth }} </v-card-text>
              <v-card-text>{{ $t('district') }} : {{ student.district_birth }}</v-card-text>
            </v-col>
            <v-col>
              <v-card-text>{{ $t('commune') }} : {{ student.commune_birth }} </v-card-text>
              <v-card-text>{{ $t('province') }} : {{ student.province_birth }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
        <v-col>
          <v-row class="align-center mx-1">
            <v-card-title
              class="bg-primary rounded-pill w-25 text-center"
              style="padding: 2px"
              >{{ $t('add') }}</v-card-title
            >
            <v-card-title
              class="bg-primary rounded-pill w-75"
              style="margin-left: -3px; font-size: 12px; padding: 3px"
            >
            </v-card-title>
          </v-row>
          <v-row>
            <v-col>
              <v-card-text>{{ $t('village') }} : {{ student.village_birth }} </v-card-text>
              <v-card-text>{{ $t('district') }} : {{ student.district_birth }}</v-card-text>
            </v-col>
            <v-col>
              <v-card-text>{{ $t('commune') }} : {{ student.commune_birth }} </v-card-text>
              <v-card-text>{{ $t('province') }} : {{ student.province_birth }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row class="mx-2">
        <v-col col="12">
          <v-row class="align-center mx-1">
            <v-card-title
              class="bg-primary rounded-pill w-25 text-center"
              style="padding: 2px"
              >{{ $t('father_infor') }}</v-card-title
            >
            <v-card-title
              class="bg-primary rounded-pill w-75"
              style="margin-left: -3px; font-size: 12px; padding: 3px"
            >
            </v-card-title>
          </v-row>
          <v-row>
            <v-col>
              <v-card-text
                >{{ $t('headers.name') }} : {{ student.d_last_name + ' ' + student.d_first_name }}</v-card-text
              >
              <v-card-text>{{ $t('job') }} : {{ student.d_job }}</v-card-text>
              <v-card-text>{{ $t('headers.phone_number') }} : {{ student.d_phone_number }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
        <v-col>
          <v-row class="align-center mx-1">
            <v-card-title
              class="bg-primary rounded-pill w-25 text-center"
              style="padding: 2px"
              >{{ $t('mother_infor') }}</v-card-title
            >
            <v-card-title
              class="bg-primary rounded-pill w-75"
              style="margin-left: -3px; font-size: 12px; padding: 3px"
            >
            </v-card-title>
          </v-row>
          <v-row>
            <v-col>
              <v-card-text
                >{{ $t('headers.name') }} : {{ student.m_last_name + ' ' + student.m_first_name }}</v-card-text
              >
              <v-card-text>{{ $t('job') }} : {{ student.m_job }}</v-card-text>
              <v-card-text>{{ $t('headers.phone_number') }} : {{ student.m_phone_number }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row class="mx-2">
        <v-col cols="12">
          <v-row class="align-center mx-1">
            <v-card-title
              class="bg-primary rounded-pill w-25 text-center"
              style="padding: 3px"
              >{{ $t('guardian') }}</v-card-title
            >
            <v-card-title
              class="bg-primary rounded-pill w-75"
              style="margin-left: -3px; padding: 3px"
            >
            </v-card-title>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="4"
            >
              <v-card-text
                >{{ $t('headers.name') }} : {{ student.g_last_name + ' ' + student.g_first_name }}</v-card-text
              >
              <v-card-text>{{ $t('detail') }} : {{ student.g_detail }}</v-card-text>
            </v-col>
            <v-col
              cols="12"
              md="2"
            >
              <v-card-text>{{ $t('headers.gender') }} : {{ student.g_gender }}</v-card-text>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-card-text>{{ $t('headers.phone_number') }} : {{ student.g_phone_number }}</v-card-text>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>
