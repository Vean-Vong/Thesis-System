<script setup>
import api from '@/plugins/utilites'
import { onMounted, ref, watch } from 'vue'
import _ from 'lodash'
import { useRouter } from 'vue-router'
const currentPage = ref(1)
const headers = ['headers.id', 'headers.name', 'Sex', 'headers.phone_number', 'headers.occupation', 'headers.action']

const data = ref([])
const numPages = ref(0)
const perPage = ref(10)
const router = useRouter()
const confirmDialog = ref(false)
const user_id = ref('')
const action = ref(null)
const loading = ref(false)
const total = ref(null)
const to = ref(null)
const from = ref(null)
const search = ref(null)

const q = () => {
  fetchData()
}

const fetchData = () => {
  loading.value = true
  api
    .post(`teachers-list`, {
      perPage: perPage.value,
      page: currentPage.value,
      search: search.value,
    })
    .then(res => {
      data.value = res.data?.data?.data
      total.value = res.data?.data?.total
      from.value = res.data?.data?.from
      to.value = res.data?.data?.to
      numPages.value = Math.ceil(res.data.data.total / perPage.value)
    })
    .finally(() => {
      loading.value = false
    })
}

watch(currentPage, (newValue, oldValue) => {
  if (newValue) {
    fetchData()
  }
})

const edit = id => {
  router.push(`/teacher/${id}`)
}
const show = id => {
  router.push(`/teacher/detail?id=${id}`)
}

const onDelete = id => {
  action.value = 1
  user_id.value = id
  confirmDialog.value = true
}

const confirmAction = () => {
  api
    .post('teachers-delete', {
      id: user_id.value,
    })
    .then(res => {
      fetchData()
    })
    .finally(() => {
      confirmDialog.value = false
      user_id.value = ''
      action.value = null
    })
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <div>
    <VCard
      :title="$t('vcard.title2')"
      class="mb-5"
    >
      <VDivider />
      <VCard-text>
        <VRow justify="start">
          <VCol
            cols="12"
            md="4"
          >
            <VTextField
              v-model="search"
              :placeholder="$t('Search')"
              append-inner-icon="mdi-search"
              @keypress.enter="q"
              @click:append-inner="q"
            />
          </VCol>
          <VCol
            cols="12"
            md="4"
          ></VCol>
          <v-col
            cols="6"
            md="4"
            class="text-end"
          >
            <VBtn
              size="large"
              variant="elevated"
              prepend-icon="mdi-plus"
              color="info"
              to="teacher/create"
            >
              {{ $t('add new') }}
            </VBtn></v-col
          >
        </VRow>
        <VTable
          :headers="headers"
          :items="data"
          item-key="fullName"
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
            </tr>
          </thead>

          <tbody>
            <td
              v-if="loading"
              :colspan="headers.length"
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
                :colspan="headers.length"
                class="text-caption text-center"
              >
                {{ $t('No data stored') }}
              </td>
            </tr>
            <tr
              v-for="row in data"
              :key="row.id"
            >
              <td v-text="row.code" />
              <td v-text="row.name" />
              <td v-text="row.sex_text" />
              <td v-text="row.phone" />
              <td v-text="row.position_text" />
              <td>
                <v-btn
                  @click="show(row.id)"
                  color="white"
                  elevation="0"
                  flat
                >
                  <v-icon color="grey">mdi-eye</v-icon>
                  <v-tooltip
                    activator="parent"
                    location="bottom"
                  >
                    {{ $t('checked') }}
                  </v-tooltip>
                </v-btn>
                <v-btn
                  @click="edit(row.id)"
                  color="white"
                  elevation="0"
                  flat
                >
                  <v-icon color="success">mdi-square-edit-outline</v-icon>
                  <v-tooltip
                    activator="parent"
                    location="bottom"
                  >
                    {{ $t('edit') }}
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
                    {{ $t('delete') }}
                  </v-tooltip>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </VTable>
      </VCard-text>
      <VCard-actions>
        <v-row>
          <v-col
            cols="12"
            lg="6"
            md="12"
            sm="12"
            xs="12"
            class="mt-3 text-center"
          >
            <span v-if="!loading">{{ from }} - {{ to }} {{ total === 0 ? '' : `of ${total}` }}</span></v-col
          >
          <v-col
            cols="12"
            lg="6"
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
    </VCard>

    <v-dialog
      v-model="confirmDialog"
      style="max-width: 500px"
      persistent
    >
      <v-card>
        <v-card-text> {{ $t('delete_teacher') }} </v-card-text>
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
  title: Teacher
  layout: default
  subject: Auth
  active: 'teacher'
</route>
