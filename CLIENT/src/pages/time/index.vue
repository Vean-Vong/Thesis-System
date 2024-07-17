<script setup>
import api from '@/plugins/utilites'
import { onMounted, reactive, ref, watch } from 'vue'
import _ from 'lodash'
import { useRouter } from 'vue-router'
const currentPage = ref(1)
const headers = ['time', 'headers.action']

const form = reactive({
  filter: null,
})

const data = ref([])
const numPages = ref(0)
const perPage = ref(10)
const router = useRouter()
const confirmDialog = ref(false)
const role_id = ref('')
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
    .post(`time-list`, {
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

const show = id => {
  router.push(`/time/${id}`)
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <div>
    <v-row>
      <v-col cols="12" md="6" sm="12">
        <VCard :title="$t('time1')" class="mb-5">
          <VDivider />
          <VCard-text>
            <VRow justify="start">
              <VCol cols="12" md="4">
                <VTextField v-model="search" :placeholder="$t('Search')" append-inner-icon="mdi-search"
                  @keypress.enter="q" @click:append-inner="q" @update:modelValue="fetchData"/>
              </VCol>
              <VCol cols="12" md="4"></VCol>
              <v-col cols="6" md="4" class="text-end">
                <VBtn size="large" variant="elevated" prepend-icon="mdi-plus" color="info" to="time/create">
                  {{ $t('add new') }}
                </VBtn>
              </v-col>
            </VRow>

            <VTable :headers="headers" :items="data" item-key="fullName" class="table-rounded">
              <thead>
                <tr>
                  <th v-for="header in headers" :key="header">
                    {{ $t(header) }}
                  </th>
                </tr>
              </thead>

              <tbody>
                <td v-if="loading" :colspan="headers.length">
                  <v-progress-linear indeterminate class="line"></v-progress-linear>
                </td>
                <tr v-if="loading && data.length === 0">
                  <td :colspan="headers.length" class="text-center">
                    <div class="text-subtitle-2">{{ $t('in progress') }}</div>
                  </td>
                </tr>
                <tr v-if="!loading && data.length === 0">
                  <td :colspan="headers.length" class="text-caption text-center">
                    {{ $t('No data stored') }}
                  </td>
                </tr>
                <tr v-for="row in data" :key="row.id">
                  <td v-text="row.time" />
                  <td>
                    <v-btn @click="show(row.id)" color="white" elevation="0" flat>
                      <v-icon color="success">mdi-square-edit-outline</v-icon>
                      <v-tooltip activator="parent" location="bottom">
                        {{ $t('edit') }}
                      </v-tooltip>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </VTable>
          </VCard-text>
          <VCard-actions>
            <v-row>
              <v-col cols="12" lg="6" md="12" sm="12" xs="12" class="mt-3 text-center">
                <span v-if="!loading">{{ from }} - {{ to }} {{ total === 0 ? '' : `of ${total}` }}</span></v-col>
              <v-col cols="12" lg="6" md="12" sm="12" xs="12">
                <VPagination v-model="currentPage" class="ml-auto" :length="numPages" :total-visible="10" />
              </v-col>
            </v-row>
          </VCard-actions>
        </VCard>
      </v-col>
    </v-row>
  </div>
</template>
<route lang="yaml">
meta:
  title: Time
  layout: default
  subject: Auth
  active: 'time'
</route>
