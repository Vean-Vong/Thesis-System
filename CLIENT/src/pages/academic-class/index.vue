<script setup>
import api from '@/plugins/utilites'
import { onMounted, reactive, ref, watch } from 'vue'
import _ from 'lodash'
import User from '../../class/User'
import { useAuthStore } from '@/plugins/auth.module'
import { useRouter } from 'vue-router'
const currentPage = ref(1)
const headers = ['headers.name', 'teacher', 'room', 'headers.action']

const form = reactive({
  filter: null,
})

const data = ref([])
const numPages = ref(0)
const perPage = ref(10)
const router = useRouter()
const confirmDialog = ref(false)
const delete_item = ref(null)
const loading = ref(false)
const total = ref(null)
const to = ref(null)
const from = ref(null)
const academic_years = ref([])
const academic_year_id = ref(null)
const search = ref(null)
const store = useAuthStore()

const user = computed(() => {
  const data = {
    user: store?._user,
  }
  return new User(data)
})
const q = () => {
  fetchData()
}

const fetchData = () => {
  loading.value = true
  api
    .post(`academic-classes-list`, {
      perPage: perPage.value,
      page: currentPage.value,
      search: search.value,
      academic_year_id: academic_year_id.value,
    })
    .then(res => {
      data.value = res.data?.data?.data
      total.value = res.data?.data?.total
      from.value = res.data?.data?.from
      to.value = res.data?.data?.to
      numPages.value = Math.ceil(res.data.data.total / perPage.value)
      academic_years.value = res.data.academic_years
      academic_year_id.value = res.data.academic_year_id
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
  router.push(`/academic-class/${id}`)
}

const show = id => {
  router.push(`/academic-class/detail/${id}`)
}

const onDelete = id => {
  confirmDialog.value = true
  delete_item.value = id
}

const confirmDelete = () => {
  api
    .post('academic-classes-delete', {
      id: delete_item.value,
    })
    .then(res => {
      fetchData()
    })
    .finally(() => {
      confirmDialog.value = false
      delete_item.value = null
    })
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <div>
    <v-row>
      <v-col
        cols="12"
        md="10"
        lg="10"
        sm="12"
      >
        <VCard
          :title="$t('vcard.title1')"
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
                  @update:modelValue="fetchData"
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <v-autocomplete
                  v-model="academic_year_id"
                  :items="academic_years"
                  item-value="id"
                  item-title="name"
                  :placeholder="$t('academic_year')"
                  @update:modelValue="fetchData"
                />
              </VCol>

              <v-col
                cols="6"
                md="2"
                class="text-start"
              >
                <VBtn
                  size="large"
                  variant="outlined"
                  prepend-icon="mdi-search"
                  color="info"
                  @click="q"
                >
                  {{ $t('Search') }}
                </VBtn></v-col
              >
              <v-col
                cols="6"
                md="3"
                class="text-end"
              >
                <VBtn
                  size="large"
                  variant="elevated"
                  prepend-icon="mdi-plus"
                  color="info"
                  to="academic-class/create"
                >
                  {{ $t('add new') }}
                </VBtn></v-col
              >
              <!-- <VBtn
                  size="large"
                  variant="elevated"
                  prepend-icon="mdi-plus"
                  color="info"
                  to="academic-class/create"
                  v-if="user.can('academic_class_create')"
                  
                >
                  {{ $t('add new') }}
                </VBtn></v-col
              > -->
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
                    {{ $t('No data stored') }} ...
                  </td>
                </tr>
                <tr
                  v-for="row in data"
                  :key="row.id"
                >
                  <td v-text="row.name" />
                  <td v-text="row.teacher.name" />
                  <td v-text="row.room.room" />
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
                    <!-- <v-btn
                      v-if="user.can('academic_class_edit')"
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
                        កែប្រែ
                      </v-tooltip>
                    </v-btn> -->
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
                    <!-- <v-btn
                      v-if="user.can('academic_class_delete')"
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
                        លុប
                      </v-tooltip>
                    </v-btn> -->
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
      </v-col>
    </v-row>

    <v-dialog
      v-model="confirmDialog"
      style="max-width: 500px"
      persistent
    >
      <v-card>
        <v-card-text> {{ $t('delete_class') }} </v-card-text>
        <v-card-actions class="ml-auto">
          <v-btn
            color="error"
            @click="confirmDialog = false"
            >{{ $t('no') }}</v-btn
          >
          <v-btn
            color="success"
            @click="confirmDelete"
            >{{ $t('yes') }}</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<route lang="yaml">
meta:
  title: academic-class
  layout: default
  subject: Auth
  active: 'academic-class'
</route>
