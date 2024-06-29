<script setup>
import api from '@/plugins/utilites'
import { reactive, ref, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import User from '../../../class/User'
import { useAuthStore } from '@/plugins/auth.module'
import moment from 'moment'
const router = useRouter()
const { params } = useRoute()
const action = ref(null)
const submitting = ref(false)
const model = ref({})
const search = ref('')
const currentPage = ref(1)
const data = ref([])
const students = ref([])
const numPages = ref(0)
const perPage = ref(10)
const loading = ref(false)
const loading2 = ref(false)
const adding_student = ref(false)
const total = ref(null)
const to = ref(null)
const from = ref(null)
const refForm = ref()
const delete_item = ref(null)
const confirmDialog = ref(false)
const dialog_add_student = ref(false)
const headers = ['Add', 'headers.id', 'headers.name', 'headers.gender', 'dob']
const headers2 = ['headers.id', 'headers.name', 'headers.gender', 'dob', 'status', 'is_new', 'headers.action']
const selected_students = ref([])
const listing = ref(false)
const store = useAuthStore()
const Type = ref(null)
const formatDate = date => {
  return moment(date).format('D MMM YYYY')
}
const fetchData = () => {
  api
    .post('academic-classes-detail', {
      id: params.id,
    })
    .then(res => {
      model.value = res.data.model
      Type.value = res.data.model.type //fetch type in api
    })
  listingStudent()
}

const user = computed(() => {
  const data = {
    user: store?._user,
  }
  return new User(data)
})

const searchStudent = () => {
  loading.value = true
  api
    .post('academic-classes-search-student', {
      search: search.value,
      perPage: perPage.value,
      page: currentPage.value,
      academic_year_id: model.value.academic_year.id,
    })
    .then(response => {
      data.value = response.data.data.data
      total.value = response.data.data.total
      from.value = response.data.data.from
      to.value = response.data.data.to
      numPages.value = Math.ceil(response.data.data.total / perPage.value)
    })
    .finally(() => {
      loading.value = false
    })
}

const updateSearch = value => {
  search.value = value
  searchStudent()
}

const onConfirmAddStudent = () => {
  adding_student.value = true
  api
    .post('academic-classes-add-student', {
      academic_class_id: model.value.id,
      student_ids: selected_students.value,
    })
    .then(res => {
      listingStudent()
      dialog_add_student.value = false
    })
    .catch(res => {
      dialog_add_student.value = false
    })
    .finally(() => {
      adding_student.value = false
    })
}

const q = () => {
  searchStudent()
}

const qStudent = () => {
  listingStudent()
}

const onAddStudent = () => {
  searchStudent()
  dialog_add_student.value = true
}

watch(dialog_add_student, newValue => {
  search.value = null
})

const onDelete = id => {
  action.value = 1
  delete_item.value = id
  confirmDialog.value = true
}

const onMoveStudent = id => {
  action.value = 2
  delete_item.value = id
  confirmDialog.value = true
}
const onStopStudent = id => {
  action.value = 3
  delete_item.value = id
  confirmDialog.value = true
}
const onNewStudent = id => {
  action.value = 4
  delete_item.value = id
  confirmDialog.value = true
}

const confirmAction = () => {
  if (action.value === 1) {
    api
      .post('academic-classes-remove-student', {
        id: delete_item.value,
      })
      .then(res => {
        listingStudent()
      })
      .finally(() => {
        confirmDialog.value = false
        delete_item.value = null
      })
  } else if (action.value === 2) {
    api
      .post('academic-classes-move-student', {
        id: delete_item.value,
      })
      .then(res => {
        listingStudent()
      })
      .finally(() => {
        confirmDialog.value = false
        delete_item.value = null
      })
  } else if (action.value === 3) {
    api
      .post('academic-classes-make-as-stop-student', {
        id: delete_item.value,
      })
      .then(res => {
        listingStudent()
      })
      .finally(() => {
        confirmDialog.value = false
        delete_item.value = null
      })
  } else if (action.value === 4) {
    api
      .post('academic-classes-make-as-new-student', {
        id: delete_item.value,
      })
      .then(res => {
        listingStudent()
      })
      .finally(() => {
        confirmDialog.value = false
        delete_item.value = null
      })
  }
}

const listingStudent = () => {
  listing.value = true
  loading2.value = true
  api
    .post('academic-classes-list-student', {
      search: search.value,
      academic_class_id: params.id,
    })
    .then(response => {
      students.value = response.data.data
    })
    .finally(() => {
      loading2.value = false
    })
}

const addScore = () => {
  let type
  if (Type.value === 1) {
    type = 'score1'
  } else if (Type.value === 2) {
    type = 'score2'
  } else if (Type.value === 3) {
    type = 'score3'
  }
  router.push(`/academic-class/detail/${type}/create?id=${params.id}`)
}

const listingScore = () => {
  let type
  if (Type.value === 1) {
    type = 'score1'
  } else if (Type.value === 2) {
    type = 'score2'
  } else if (Type.value === 3) {
    type = 'score3'
  }
  router.push(`/academic-class/detail/${type}/show?id=${params.id}`)
}

const onAddAttendance = () => {
  router.push('/academic-class/detail/attendance/' + params.id)
}

const listingAttendance = () => {
  router.push('/academic-class/detail/attendance/' + params.id + '_' + 'c')
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <div>
    <VRow>
      <VCol
        cols="12"
        md="12"
        sm="12"
      >
        <VCard :title="`${$t('class')} ${model.name} ${$t('academic_year')} ${model.academic_year?.name}`">
          <VDivider />
          <v-btn
            class="mt-1 mx-5"
            color="secondary"
            variant="outlined"
            @click="$router.go(-1)"
            ><v-icon>mdi-arrow-back</v-icon>&nbsp;{{ $t('back') }}</v-btn
          >
          <VCardText class="py-0">
            <v-row class="my-5 justify-end">
              <v-btn
                class="mx-5"
                color="grey"
                @click="listingScore()"
                ><v-icon>mdi-eye</v-icon>&nbsp;{{ $t('list_score') }}</v-btn
              >
              <v-btn
                color="grey"
                @click="listingAttendance()"
                ><v-icon>mdi-eye</v-icon>&nbsp;{{ $t('list_att') }}</v-btn
              >
            </v-row>
            <v-row class="my-2 justify-end">
              <v-btn
                class="mx-5"
                color="success"
                @click="addScore()"
                ><v-icon>mdi-add</v-icon>&nbsp;{{ $t('insert_score') }}</v-btn
              >
              <v-btn
                color="success"
                @click="onAddAttendance()"
                ><v-icon>mdi-add</v-icon>&nbsp;{{ $t('insert_att') }}</v-btn
              >
            </v-row>
          </VCardText>
          <v-card-text v-if="listing">
            <div class="text-center font-weight-bold text-h6">{{ $t('list_student') }}</div>
            <VDivider
              class="my-2"
              :thickness="1"
            />
            <v-row>
              <v-col
                cols="4"
                md="4"
                lg="4"
                sm="4"
              >
                <VTextField
                  v-model="search"
                  :placeholder="$t('Search')"
                  append-inner-icon="mdi-search"
                  @keypress.enter="qStudent"
                  @click:append-inner="qStudent"
                  @update:modelValue="fetchData"
                />
              </v-col>
              <v-spacer />
              <v-col
                cols="4"
                md="4"
                lg="4"
                sm="4"
                align="end"
              >
                <v-btn
                  color="success"
                  @click="onAddStudent()"
                  ><v-icon>mdi-add</v-icon>&nbsp;{{ $t('insert_student') }}</v-btn
                >
              </v-col>
            </v-row>
            <VTable
              :headers="headers2"
              :items="students"
              class="table-rounded"
            >
              <thead>
                <tr>
                  <th
                    v-for="header in headers2"
                    :key="header"
                  >
                    {{ $t(header) }}
                  </th>
                </tr>
              </thead>

              <tbody>
                <td
                  v-if="loading2"
                  :colspan="headers2.length + 1"
                >
                  <v-progress-linear
                    indeterminate
                    class="line"
                  ></v-progress-linear>
                </td>
                <tr v-if="loading2 && headers2.length === 0">
                  <td
                    :colspan="headers.length"
                    class="text-center"
                  >
                    <div class="text-subtitle-2">{{ $t('in progress') }}</div>
                  </td>
                </tr>
                <tr v-if="!loading && students.length === 0">
                  <td
                    :colspan="headers.length + 1"
                    class="text-subtitle-2 text-center"
                  >
                    {{ $t('No data stored') }}
                  </td>
                </tr>
                <tr
                  v-for="row in students"
                  :key="row.id"
                >
                  <td v-text="row.student?.code" />
                  <td v-text="row.student?.last_name + ' ' + row.student?.first_name" />
                  <td v-text="row.student?.sex_text" />
                  <td v-text="formatDate(row.student?.dob)" />
                  <td>
                    <span v-if="row.status == 0">Transfer</span>
                    <span v-if="row.status == 1">Studying</span>
                    <span v-if="row.status == 2">Stop</span>
                  </td>
                  <td v-text="row.is_new == 1 ? 'New' : ''" />
                  <td v-if="row.status != 0">
                    <v-btn
                      v-if="row.is_new != 1"
                      @click="onNewStudent(row.id)"
                      color="white"
                      elevation="0"
                      flat
                    >
                      <v-icon color="error">mdi-new-box</v-icon>
                      <v-tooltip
                        activator="parent"
                        location="bottom"
                      >
                        {{ $t('make_as_new_student') }}
                      </v-tooltip>
                    </v-btn>
                    <v-btn
                      v-if="row.status != 2"
                      @click="onStopStudent(row.id)"
                      color="white"
                      elevation="0"
                      flat
                    >
                      <v-icon color="error">mdi-stop-circle</v-icon>
                      <v-tooltip
                        activator="parent"
                        location="bottom"
                      >
                        {{ $t('make_as_stop_student') }}
                      </v-tooltip>
                    </v-btn>
                    <v-btn
                      @click="onMoveStudent(row.id)"
                      color="white"
                      elevation="0"
                      flat
                    >
                      <v-icon color="warning">mdi-forwardburger</v-icon>
                      <v-tooltip
                        activator="parent"
                        location="bottom"
                      >
                        {{ $t('transfer_students') }}
                      </v-tooltip>
                    </v-btn>
                    <v-btn
                      @click="onDelete(row.id)"
                      color="white"
                      elevation="0"
                      flat
                    >
                      <v-icon color="error">mdi-trash</v-icon>
                      <v-tooltip
                        activator="parent"
                        location="bottom"
                      >
                        {{ $t('remove_student') }}
                      </v-tooltip>
                    </v-btn>
                  </td>
                </tr>
                <tr></tr>
              </tbody>
            </VTable>
          </v-card-text>
        </VCard>
      </VCol>
    </VRow>
    <v-dialog
      v-model="dialog_add_student"
      style="max-width: 1000px"
      persistent
    >
      <v-card>
        <v-card-title style="display: flex; justify-content: space-between; align-items: center"
          ><span>{{ $t('select_student') }}</span
          ><v-btn
            color="primary"
            variant="text"
            @click="dialog_add_student = false"
          >
            <VIcon>mdi-close</VIcon>
          </v-btn></v-card-title
        >
        <v-divider></v-divider>
        <v-card-text>
          <v-row>
            <v-col
              cols="12"
              lg="9"
              md="9"
              sm="9"
            >
              <v-row>
                <v-col
                  cols="6"
                  md="6"
                  lg="6"
                  sm="6"
                >
                  <VTextField
                    v-model="search"
                    :placeholder="$t('Search')"
                    append-inner-icon="mdi-search"
                    @keypress.enter="q"
                    @click:append-inner="q"
                    @update:modelValue="searchStudent"
                  />
                </v-col>
              </v-row>
            </v-col>
            <v-col
              cols="12"
              lg="3"
              md="3"
              sm="3"
            >
              <v-btn
                color="info"
                size="large"
                @click="onConfirmAddStudent()"
                :loading="adding_student"
                ><v-icon>mdi-add</v-icon>&nbsp;{{ $t('insert_student_to_class') }}</v-btn
              ></v-col
            >
            <v-col
              cols="12"
              md="12"
            >
              <VTable
                :headers="headers"
                :items="data"
                class="table-rounded"
              >
                <thead>
                  <tr>
                    <th
                      v-for="header in headers"
                      :key="header"
                    >
                      {{ $t(header) }}
                    </th>
                    <th class="text-right"></th>
                  </tr>
                </thead>

                <tbody>
                  <td
                    v-if="loading"
                    :colspan="headers.length + 1"
                  >
                    <v-progress-linear
                      indeterminate
                      class="line"
                    ></v-progress-linear>
                  </td>
                  <tr v-if="loading && data.length === 0">
                    <td
                      :colspan="headers.length"
                      class="text-center"
                    >
                      <div class="text-subtitle-2">{{ $t('in progress') }}</div>
                    </td>
                  </tr>
                  <tr v-if="!loading && data.length === 0">
                    <td
                      :colspan="headers.length + 1"
                      class="text-subtitle-2 text-center"
                    >
                      {{ $t('No data stored') }}
                    </td>
                  </tr>
                  <tr
                    v-for="row in data"
                    :key="row.id"
                  >
                    <td>
                      <v-checkbox
                        :value="row.id"
                        v-model="selected_students"
                      ></v-checkbox>
                    </td>
                    <td v-text="row.code" />
                    <td v-text="row.last_name + ' ' + row.first_name" />
                    <td v-text="row.sex_text" />
                    <td v-text="formatDate(row.dob)" />
                  </tr>
                  <tr></tr>
                </tbody>
              </VTable>
            </v-col>
          </v-row>
        </v-card-text>
        <VCard-actions>
          <v-row>
            <v-col
              cols="12"
              lg="3"
              md="12"
              sm="12"
              xs="12"
              class="mt-3 text-center"
            >
              <span v-if="!loading">{{ from }} - {{ to }} {{ total === 0 ? '' : `of ${total}` }}</span></v-col
            >
            <v-col
              cols="12"
              lg="9"
              md="12"
              sm="12"
              xs="12"
            >
              <VPagination
                v-model="currentPage"
                class="ml-auto"
                :length="numPages"
                :total-visible="10"
              />
            </v-col>
          </v-row>
        </VCard-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="confirmDialog"
      style="max-width: 500px"
      persistent
    >
      <v-card>
        <v-card-text>
          {{
            action === 1
              ? $t('confirm_exclude')
              : action === 2
              ? $t('confirm_transfer')
              : action === 3
              ? $t('confirm_stop_student')
              : action === 4
              ? $t('confirm_new_student')
              : $t('confirm_exclude')
          }}
        </v-card-text>
        <v-card-actions class="ml-auto">
          <v-btn
            color="error"
            @click="confirmDialog = false"
            >{{ $t('no') }}</v-btn
          >
          <v-btn
            color="success"
            @click="confirmAction"
            >{{ $t('yes') }}</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<route lang="yaml">
meta:
  title: Study Class
  layout: default
  subject: Auth
  active: 'academic-class'
</route>
