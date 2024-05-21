<script setup>
import avatar1 from '@/assets/images/avatars/avatar-1.png'
import { onMounted, ref, reactive, computed } from 'vue'
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
  {
    id: 1,
    name: 'ប្រុស',
  },
  {
    id: 2,
    name: 'ស្រី',
  },
])
const status = ref([
  {
    id: 1,
    name: '1',
  },
  {
    id: 2,
    name: '2',
  },
])

const form = {
  code: null,
  name: null,
  name_latin: null,
  sex: null,
  dob: null,
  pob: null,
  address: null,
  photo_path: null,
  dad_name: null,
  dad_phone: null,
  mom_name: null,
  mom_phone: null,
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

    formData.append('code', formDataLocal.value.code)
    formData.append('name', formDataLocal.value.name)
    formData.append('sex', formDataLocal.value.sex)
    if (formDataLocal.value.photo_path) {
      formData.append('photo_path', formDataLocal.value.photo_path)
    }
    if (formDataLocal.value.name_latin) {
      formData.append('name_latin', formDataLocal.value.name_latin)
    }
    if (formDataLocal.value.dob) {
      formData.append('dob', formDataLocal.value.dob)
    }
    if (formDataLocal.value.pob) {
      formData.append('pob', formDataLocal.value.pob)
    }
    if (formDataLocal.value.address) {
      formData.append('address', formDataLocal.value.address)
    }
    if (formDataLocal.value.dad_name) {
      formData.append('dad_name', formDataLocal.value.dad_name)
    }
    if (formDataLocal.value.dad_phone) {
      formData.append('dad_phone', formDataLocal.value.dad_phone)
    }
    if (formDataLocal.value.mom_name) {
      formData.append('mom_name', formDataLocal.value.mom_name)
    }
    if (formDataLocal.value.mom_phone) {
      formData.append('mom_phone', formDataLocal.value.mom_phone)
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
                <!-- <VCol md="6" cols="12"></VCol> -->
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
                <!-- <VCol
                md="3"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.name"
                  :label="$t('khmer_name')"
                  :rules="[(v) => !!v || 'ឈ្មោះភាសាខ្មែរ តម្រូវឱ្យបំពេញ']"
                />
              </VCol> -->
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.name_latin_last"
                    label="Last Name Latin"
                    :rules="[v => !!v || 'ឈ្មោះឡាតាំង តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <!-- <VCol
                md="3"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.name_latin"
                  :label="$t('latin_name')"
                />
              </VCol> -->
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.name_latin_first"
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
                    v-model="formDataLocal.sex"
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
                  />
                </VCol>
                <VCol
                  md="3"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.phoneNumber"
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
                    :rules="[v => !!v || 'ទីកន្លែង តម្រូវឱ្យបំពេញ']"
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
                    :rules="[v => !!v || 'ស្ថានភាពសិស្ស តម្រូវឱ្យបំពេញ']"
                  />
                </VCol>
                <VCol
                  md="4"
                  cols="12"
                >
                  <VTextField
                    v-model="formDataLocal.other"
                    label="Other"
                    :rules="[v => !!v || 'ផ្សេងៗ តម្រូវឱ្យបំពេញ']"
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
                    v-model="formDataLocal.commnue_birth"
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
                    v-model="formDataLocal.commnue"
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
            </VForm>
          </VCard>
          <Vform
            ref="refForm"
            class="my-4 mx-3"
            lazy-validation
            @submit.prevent="submitHandler"
          >
            <VRow>
              <VCol
                md="6"
                cols="12"
              >
                <VCard>
                  <div class="mx-3 my-4">
                    <h3>Father's Information</h3>
                    <VRow>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.lastName_father"
                          label="Last Name"
                          :rules="[v => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                        />
                      </VCol>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.firstName_father"
                          label="First Name"
                          :rules="[v => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                        />
                      </VCol>
                    </VRow>
                    <VRow>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.job_father"
                          label="Job"
                          :rules="[v => !!v || 'ការងារ តម្រូវឱ្យបំពេញ']"
                        />
                      </VCol>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.dad_phone"
                          label="Phone Number"
                          :rules="[v => !!v || 'លេខទូរសព្ទ តម្រូវឱ្យបំពេញ']"
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
                    <h3>Mother's Information</h3>
                    <VRow>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.lastName_mother"
                          label="Last Name"
                          :rules="[v => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                        />
                      </VCol>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.firstName_mother"
                          label="First Name"
                          :rules="[v => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                        />
                      </VCol>
                    </VRow>
                    <VRow>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.job_mother"
                          label="Job"
                          :rules="[v => !!v || 'ការងារ តម្រូវឱ្យបំពេញ']"
                        />
                      </VCol>
                      <VCol
                        md="6"
                        cols="12"
                      >
                        <VTextField
                          v-model="formDataLocal.mom_phone"
                          label="Phone Number"
                          :rules="[v => !!v || 'លេខទូរសព្ទ តម្រូវឱ្យបំពេញ']"
                        />
                      </VCol>
                    </VRow>
                  </div>
                </VCard>
              </VCol>
            </VRow>
          </Vform>
          <VForm
            ref="refForm"
            class="mt-1 mb-6"
            lazy-validation
            @submit.prevent="submitHandler"
          >
            <VCard>
              <div class="mx-3 my-4">
                <h3>Guardian</h3>
                <VRow>
                  <VCol
                    md="6"
                    cols="12"
                  >
                    <VTextField
                      v-model="formDataLocal.lastName_guardian"
                      label="Last Name"
                      :rules="[v => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                    />
                  </VCol>
                  <VCol
                    md="6"
                    cols="12"
                  >
                    <VTextField
                      v-model="formDataLocal.firstName_guardian"
                      label="First Name"
                      :rules="[v => !!v || 'ឈ្មោះ តម្រូវឱ្យបំពេញ']"
                    />
                  </VCol>
                  <VCol
                    md="6"
                    cols="12"
                  >
                    <VTextField
                      v-model="formDataLocal.phone_guardian"
                      label="Phone Number"
                      :rules="[v => !!v || 'លេខទូរសព្ទ តម្រូវឱ្យបំពេញ']"
                    />
                  </VCol>
                  <VCol
                    md="6"
                    cols="12"
                  >
                    <VTextField
                      v-model="formDataLocal.job_guardian"
                      label="Job"
                      :rules="[v => !!v || 'ការងារ តម្រូវឱ្យបំពេញ']"
                    />
                  </VCol>
                  <VCol
                    md="4"
                    cols="12"
                  >
                    <VSelect
                      v-model="formDataLocal.sex"
                      :items="sexs"
                      item-title="name"
                      item-value="id"
                      :label="$t('Sex')"
                      :rules="[v => !!v || 'ភេទ តម្រូវឱ្យបំពេញ']"
                    />
                  </VCol>
                  <VCol
                    md="8"
                    cols="12"
                  >
                    <VTextField
                      v-model="formDataLocal.detail_guardian"
                      label="Detail"
                      :rules="[v => !!v || 'លម្អិត តម្រូវឱ្យបំពេញ']"
                    />
                  </VCol>
                </VRow>
              </div>
            </VCard>
          </VForm>

          <VForm>
            <VRow>
              <!-- <VCol cols="12">
                <VTextarea
                  v-model="formDataLocal.address"
                  :label="$t('current address')"
                  no-resize
                  rows="2"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.dad_name"
                  :label="$t('headers.fatherName')"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.dad_phone"
                  :label="$t('headers.fatherPhone')"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.mom_name"
                  :label="$t('headers.motherName')"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.mom_phone"
                  :label="$t('headers.motherPhone')"
                />
              </VCol> -->
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
