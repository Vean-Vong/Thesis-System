<script setup>
import avatar1 from '@/assets/images/avatars/avatar-1.png'
import { ref, computed } from 'vue'
import api from '@/plugins/utilites'
import { useRouter } from 'vue-router'
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
const sexs = ref([
  { id: 1, name: 'ប្រុស' },
  { id: 2, name: 'ស្រី' },
])
const status = ref([
  { id: 1, name: '1' },
  { id: 2, name: '2' },
])

const form = {
  code: null,
  last_name: null,
  first_name: null,
  last_name_latin: null,
  first_name_latin: null,
  sex: null,
  gender: null,
  dob: null,
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
  formDataLocal.value.photo_path = null
}

const submitHandler = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    let formData = new FormData()

    for (const key in formDataLocal.value) {
      if (formDataLocal.value[key] !== null) {
        formData.append(key, formDataLocal.value[key])
      }
    }

    api
      .post('students-create', formData)
      .then(res => {
        router.push('/student')
      })
      .finally(() => {
        submitting.value = false
      })
  }
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard :title="$t('vcard.title_student')">
        <VCardText class="d-flex">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded="lg"
            size="100"
            class="me-6"
            :image="additional_image"
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
                  <h3>Personal Information</h3>
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.last_name"
                    label="Last Name"
                    :rules="[v => !!v || 'ឈ្មោះភាសាខ្មែរ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.first_name"
                    label="First Name"
                    :rules="[v => !!v || 'ឈ្មោះភាសាខ្មែរ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.last_name_latin"
                    label="Last Name Latin"
                    :rules="[v => !!v || 'ឈ្មោះឡាតាំង តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.first_name_latin"
                    label="First Name Latin"
                    :rules="[v => !!v || 'ឈ្មោះឡាតាំង តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.code"
                    :label="$t('headers.id')"
                    :rules="[v => !!v || 'អត្ថលេខ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
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
                  md="3"
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
                    v-model="formDataLocal.phone"
                    label="Phone Number"
                    :rules="[v => !!v || 'លេខទូរសព្ទ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.from"
                    label="From"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VSelect
                    v-model="formDataLocal.student_status"
                    :items="status"
                    item-title="name"
                    item-value="id"
                    label="Student Status"
                  />
                </VCol>
                <VCol
                  md="4"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.other"
                    label="Other"
                  />
                </VCol>
                <VCol
                  md="2"
                  cols="12"
                  class="mt-2"
                >
                  <input type="radio" />&nbsp;
                  <label for="">Active</label>
                </VCol>

                <VCol
                  md="12"
                  cols="12"
                >
                  <h3>Place Of Birth</h3>
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.village_birth"
                    label="Village"
                    :rules="[v => !!v || 'ភូមិ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.commune_birth"
                    label="Commune"
                    :rules="[v => !!v || 'ឃុំ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.district_birth"
                    label="District"
                    :rules="[v => !!v || 'ស្រុក តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.province_birth"
                    label="Province"
                    :rules="[v => !!v || 'ខេត្ត តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="12"
                  cols="12"
                >
                  <h3>Address</h3>
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.village"
                    label="Village"
                    :rules="[v => !!v || 'ភូមិ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.commune"
                    label="Commune"
                    :rules="[v => !!v || 'ឃុំ តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.district"
                    label="District"
                    :rules="[v => !!v || 'ស្រុក តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.province"
                    label="Province"
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
                      <h3 class="mb-5">Father's Information</h3>
                      <VRow>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.d_last_name"
                            label="Last Name"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.d_first_name"
                            label="First Name"
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
                            label="Job"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.d_phone_number"
                            label="Phone Number"
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
                      <h3 class="mb-5">Mother's Information</h3>
                      <VRow>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.m_last_name"
                            label="Last Name"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.m_first_name"
                            label="First Name"
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
                            label="Job"
                          />
                        </VCol>
                        <VCol
                          md="6"
                          cols="12"
                        >
                          <VTextField
                            v-model="formDataLocal.m_phone_number"
                            label="Phone Number"
                          />
                        </VCol>
                      </VRow>
                    </div>
                  </VCard>
                </VCol>
              </VRow>

              <VCard>
                <div class="mx-3 my-4">
                  <h3 class="mb-5">Guardian</h3>
                  <VRow>
                    <VCol
                      md="6"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_last_name"
                        label="Last Name"
                      />
                    </VCol>
                    <VCol
                      md="6"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_first_name"
                        label="First Name"
                      />
                    </VCol>
                    <VCol
                      md="6"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_phone_number"
                        label="Phone Number"
                      />
                    </VCol>
                    <VCol
                      md="6"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_job"
                        label="Job"
                      />
                    </VCol>
                    <VCol
                      md="4"
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
                      md="8"
                      cols="12"
                    >
                      <VTextField
                        v-model="formDataLocal.g_detail"
                        label="Detail"
                      />
                    </VCol>
                  </VRow>
                </div>
              </VCard>

              <VRow>
                <!-- 👉 Form Actions -->
                <VCol
                  cols="12"
                  class="d-flex flex-wrap gap-4"
                >
                  <VBtn
                    type="submit"
                    :loading="submitting"
                    color="success"
                  >
                    <VIcon>mdi-add</VIcon>
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
  title: Student List
  layout: default
  subject: Auth
  active: 'student'
</route>
