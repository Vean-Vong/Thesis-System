<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/plugins/utilites'
import moment from 'moment'
import constant from '@/constants'

const route = useRoute()

const student = ref({})
const academicClasses = ref([])

const getData = () => {
  if (route.query.id) {
    api
      .post('academic-classes-list-study-history', { student_id: route.query.id })
      .then(res => {
        if (res.data.status === 200) {
          student.value = res.data.student
          academicClasses.value = res.data.academic_classes
        } else {
          console.error(res.data.message)
        }
      })
      .catch(error => {
        console.error(error)
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
  <div>
    <VCard>
      <VCardTitle>{{ $t('student_list') }}</VCardTitle>
    </VCard>

    <v-card class="mt-7">
      <v-btn
        class="mt-4 mx-5"
        color="primary"
        variant="outlined"
        @click="$router.go(-1)"
      >
        <v-icon>mdi-arrow-back</v-icon>&nbsp;{{ $t('back') }}
      </v-btn>
      <v-divider class="mt-4"></v-divider>
      <div>
        <v-row class="mt-1">
          <v-col
            cols="12"
            md="3"
          >
            <h3 class="mb-3 mx-3">{{ $t('profile') }}</h3>
            <v-card class="pa-2">
              <v-col
                cols="12"
                class="d-flex justify-center align-center"
              >
                <v-img
                  alt="student"
                  :src="
                    student?.photo_path != null
                      ? constant.storagePath + student.photo_path
                      : 'https://cdn-icons-png.flaticon.com/512/1154/1154987.png'
                  "
                />
              </v-col>

              <v-chip
                class="mx-4"
                color="primary"
              >
                {{ $t('profile') }}
              </v-chip>
              <v-card-text>{{ $t('code') }} : {{ student.code }}</v-card-text>
              <v-card-text>{{ $t('headers.name') }} : {{ student.last_name + ' ' + student.first_name }}</v-card-text>
              <v-card-text>{{ $t('dob') }} : {{ formatDate(student.dob) }}</v-card-text>
              <v-card-text>{{ $t('headers.gender') }} : {{ student.sex_text }}</v-card-text>
              <v-card-text>{{ $t('status') }} : {{ student.student_status }}</v-card-text>
              <v-card-text>{{ $t('from') }} : {{ student.from }}</v-card-text>
              <v-card-text>{{ $t('headers.phone_number') }} : {{ student.phone }}</v-card-text>
              <v-card-text>{{ $t('other') }} : {{ student.other }}</v-card-text>
            </v-card>
          </v-col>
          <v-col
            cols="12"
            md="9"
          >
            <h3 class="mb-3 mx-3">{{ $t('study_history') }}</h3>
            <div
              v-for="(academicClass, index) in academicClasses"
              :key="index"
              class="mb-3"
            >
              <v-card class="pa-2">
                <v-card-text>
                  <v-card-title>{{ $t('academic_year') }}: {{ academicClass.academic_year?.name }}</v-card-title>
                  <div class="col">
                    <v-card-title>{{ $t('room') }}: {{ academicClass.room?.room }}</v-card-title>
                  </div>
                  <div class="col">
                    <v-card-title>{{ $t('time') }}: {{ academicClass.time.time }}</v-card-title>
                  </div>
                  <div class="col">
                    <v-card-title>{{ $t('teacher') }}: {{ academicClass.teacher?.name }}</v-card-title>
                  </div>
                </v-card-text>
              </v-card>
            </div>
          </v-col>
        </v-row>
      </div>
    </v-card>
  </div>
</template>
<route lang="yaml">
meta:
  title: Detail Student
  layout: default
  subject: Auth
  active: 'student'
</route>
