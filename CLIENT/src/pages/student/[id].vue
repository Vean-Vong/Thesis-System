<script setup>
import avatar1 from '@/assets/images/avatars/avatar-1.png'
import { onMounted, ref, computed } from 'vue'
import api from '@/plugins/utilites'
import { useRoute, useRouter } from 'vue-router'
import constant from '@/constants'
import { useAuthStore } from '@/plugins/auth.module'
import User from '../../class/User'

const store = useAuthStore()

const user = computed(() => {
  const data = {
    user: store?._user,
  }
  return new User(data)
})

const submitting = ref(false)
const additional_image = ref(avatar1)
const refForm = ref(null)
const router = useRouter()
const route = useRoute()
const sexs = ref([
  { id: 1, name: 'ប្រុស' },
  { id: 2, name: 'ស្រី' },
])
const status = ref([
  { id: 1, name: 'នៅលីវ' },
  { id: 2, name: 'មានគ្រួសារ' },
])
const form = {
  id: route.params.id,
  code: null,
  name: null,
  last_name: null,
  first_name: null,
  last_name_latin: null,
  first_name_latin: null,
  sex: null,
  dob: null,
  gender: null,
  phone: null,
  from: null,
  student_status: null,
  other: null,
  village_birth: null,
  commune_birth: null,
  district_birth: null,
  province_birth: null,
  village: null,
  commune: null,
  district: null,
  province: null,
  d_last_name: null,
  d_first_name: null,
  d_job: null,
  d_phone_number: null,
  m_last_name: null,
  m_first_name: null,
  m_job: null,
  m_phone_number: null,
  g_last_name: null,
  g_first_name: null,
  g_phone_number: null,
  g_job: null,
  g_gender: null,
  g_detail: null,
  photo_path: null,
}
const refInputEl = ref()
const formDataLocal = ref(structuredClone(form))

const resetForm = () => {
  formDataLocal.value = structuredClone(form)
}
const changeAvatar = file => {
  const fileReader = new FileReader()
  const { files } = file.target
  if (files && files.length) {
    fileReader.readAsDataURL(files[0])
    formDataLocal.value.photo_path = files[0]
    fileReader.onload = () => {
      if (typeof fileReader.result === 'string') additional_image.value = fileReader.result
    }
  }
}

// reset avatar image
const resetAvatar = () => {
  additional_image.value = avatar1
  localImage.value = null
  formDataLocal.value.photo_path = null
}
// edit student form
const submitHandler = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    let formData = new FormData()

    Object.keys(formDataLocal.value).forEach(key => {
      if (formDataLocal.value[key] !== null && formDataLocal.value[key] !== undefined) {
        formData.append(key, formDataLocal.value[key])
      }
    })
    // console.log(formDataLocal.value.photo_path)
    api
      .post('students-update', formData)
      .then(() => {
        router.push('/student')
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

const localImage = ref()

onMounted(() => {
  if (route.params.id) {
    api.post(`students-show`, { id: route.params.id }).then(res => {
      Object.assign(formDataLocal.value, { ...res.data?.model })
      formDataLocal.value.photo_path = null
      localImage.value = res.data.model.photo_path
    })
  }
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard :title="$t('update student')">
        <VCardText class="d-flex">
          <!-- 👉 Avatar -->
          <VAvatar
            v-if="!localImage || formDataLocal.photo_path"
            rounded="lg"
            size="100"
            class="me-6"
            :image="additional_image"
          />
          <VAvatar
            v-if="!formDataLocal.photo_path && localImage"
            rounded="lg"
            size="100"
            class="me-6"
            :image="constant.storagePath + localImage"
          />

          <!-- 👉 Upload Photo -->
          <div class="d-flex flex-column justify-center gap-5">
            <div class="d-flex flex-wrap gap-2">
              <VBtn
                color="success"
                @click="refInputEl?.click()"
              >
                <VIcon
                  icon="mdi-cloud-upload-outline"
                  class="d-sm-none"
                />
                <span class="d-none d-sm-block">{{ $t('upload_photo') }}</span>
              </VBtn>
              <input
                ref="refInputEl"
                type="file"
                name="file"
                accept=".jpeg,.png,.jpg,GIF"
                hidden
                @input="changeAvatar"
              />
              <VBtn
                type="reset"
                color="error"
                variant="tonal"
                @click="resetAvatar"
              >
                <span class="d-none d-sm-block">{{ $t('reset') }}</span>
                <VIcon
                  icon="mdi-refresh"
                  class="d-sm-none"
                />
              </VBtn>
            </div>
            <p class="text-body-1 mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
          </div>
        </VCardText>

        <VDivider />
        <VCardText>
          <!-- 👉 Form -->
          <VCard class="m-5">
            <VForm
              ref="refForm"
              class="my-4 mx-3"
              lazy-validation
              @submit.prevent="submitHandler"
            >
              <VRow>
                <VCol
                  md="12"
                  cols="12"
                >
                  <h3>{{ $t('personal_infor') }}</h3>
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.code"
                    :label="$t('code')"
                    :rules="[v => !!v || 'អត្ថលេខ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.last_name"
                    :label="$t('surname')"
                    :rules="[v => !!v || 'ឈ្មោះភាសាខ្មែរ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.first_name"
                    :label="$t('name')"
                    :rules="[v => !!v || 'ឈ្មោះភាសាខ្មែរ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.last_name_latin"
                    :label="$t('surname_latin')"
                    :rules="[v => !!v || 'ឈ្មោះឡាតាំង តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.first_name_latin"
                    :label="$t('name_latin')"
                    :rules="[v => !!v || 'ឈ្មោះឡាតាំង តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                >
                  <VSelect
                    v-model="formDataLocal.gender"
                    :items="sexs"
                    item-title="name"
                    item-value="id"
                    :label="$t('Sex')"
                    :rules="[v => !!v || 'ភេទ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.dob"
                    :label="$t('dob')"
                    type="date"
                    :rules="[v => !!v || 'ថ្ងៃខែឆ្នាំកំណើត តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.from"
                    :label="$t('from')"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.phone"
                    :label="$t('headers.phone_number')"
                    :rules="[v => !!v || 'លេខទូរសព្ទ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                >
                  <VSelect
                    v-model="formDataLocal.student_status"
                    :items="status"
                    item-title="$t('name')"
                    item-value="id"
                    :label="$t('status')"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.other"
                    :label="$t('other')"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                  class="mt-2"
                >
                  <input type="radio" />&nbsp;
                  <label for="">{{ $t('active') }}</label>
                </VCol>

                <VCol
                  md="12"
                  cols="12"
                >
                  <h3>{{ $t('pob') }}</h3>
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.village_birth"
                    :label="$t('village')"
                    :rules="[v => !!v || 'ភូមិ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.commune_birth"
                    :label="$t('commune')"
                    :rules="[v => !!v || 'ឃុំ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.district_birth"
                    :label="$t('district')"
                    :rules="[v => !!v || 'ស្រុក តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.province_birth"
                    :label="$t('province')"
                    :rules="[v => !!v || 'ខេត្ត តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="12"
                  cols="12"
                >
                  <h3>{{ $t('add') }}</h3>
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.village"
                    :label="$t('village')"
                    :rules="[v => !!v || 'ភូមិ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.commune"
                    :label="$t('commune')"
                    :rules="[v => !!v || 'ឃុំ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.district"
                    :label="$t('district')"
                    :rules="[v => !!v || 'ស្រុក តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.province"
                    :label="$t('province')"
                    :rules="[v => !!v || 'ខេត្ត តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
              </VRow>

              <VRow>
                <VCol
                  md="6"
                  cols="12"
                >
                  <VCard>
                    <div class="mx-3 my-4">
                      <h3 class="mb-5">{{ $t('father_infor') }}</h3>
                      <VRow>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.d_last_name"
                            :label="$t('surname')"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.d_first_name"
                            :label="$t('name')"
                          />
                        </VCol>
                      </VRow>
                      <VRow>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.d_job"
                            :label="$t('job')"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.d_phone_number"
                            :label="$t('headers.phone_number')"
                          />
                        </VCol>
                      </VRow>
                    </div>
                  </VCard>
                </VCol>
                <VCol
                  md="6"
                  cols="12"
                >
                  <VCard>
                    <div class="mx-3 my-4">
                      <h3 class="mb-5">{{ $t('mother_infor') }}</h3>
                      <VRow>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.m_last_name"
                            :label="$t('surname')"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.m_first_name"
                            :label="$t('name')"
                          />
                        </VCol>
                      </VRow>
                      <VRow>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.m_job"
                            :label="$t('job')"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.m_phone_number"
                            :label="$t('headers.phone_number')"
                          />
                        </VCol>
                      </VRow>
                    </div>
                  </VCard>
                </VCol>
              </VRow>

              <VCard class="my-6">
                <div class="mx-3 my-4">
                  <h3 class="mb-5">{{ $t('guardian') }}</h3>
                  <VRow>
                    <VCol
                      md="3"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_last_name"
                        :label="$t('surname')"
                      />
                    </VCol>
                    <VCol
                      md="3"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_first_name"
                        :label="$t('name')"
                      />
                    </VCol>
                    <VCol
                      md="2"
                      cols="12"
                    >
                      <VSelect
                        v-model="formDataLocal.g_gender"
                        :items="sexs"
                        item-title="name"
                        item-value="id"
                        :label="$t('Sex')"
                      />
                    </VCol>
                    <VCol
                      md="3"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_job"
                        :label="$t('job')"
                      />
                    </VCol>
                    <VCol
                      md="3"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_phone_number"
                        :label="$t('headers.phone_number')"
                      />
                    </VCol>
                    <VCol
                      md="5"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_detail"
                        :label="$t('detail')"
                      />
                    </VCol>
                  </VRow>
                </div>
              </VCard>

              <VRow>
                <!-- 👉 Form Actions -->
                <VCol
                  cols="12"
                  class="d-flex justify-end gap-4"
                >
                  <VBtn
                    type="submit"
                    :loading="submitting"
                    color="success"
                  >
                    <VIcon class="me-2">mdi-content-save-all</VIcon>
                    {{ $t('Save changes') }}
                  </VBtn>

                  <VBtn
                    color="secondary"
                    variant="tonal"
                    type="reset"
                    @click.prevent="resetForm"
                  >
                    {{ $t('reset') }}
                  </VBtn>
                </VCol>
              </VRow>
            </VForm>
          </VCard>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<route lang="yaml">
meta:
  title: Update Student
  layout: default
  subject: Auth
  active: 'student'
</route>
