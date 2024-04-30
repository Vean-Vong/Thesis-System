<script setup>
import api from "@/plugins/utilites"
import avatar1 from "@/assets/images/avatars/avatar-1.png"
import { ref, computed } from "vue"
import { useRouter } from "vue-router"
import { useAuthStore } from "@/plugins/auth.module"
import User from "../../class/User"

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
    name: "ប្រុស",
  },
  {
    id: 2,
    name: "ស្រី",
  },
])

const positions = ref([
  {
    id: 1,
    name: "គ្រូបង្រៀន",
  },
  {
    id: 2,
    name: "នាយករង",
  },
  {
    id: 3,
    name: "នាយក",
  },
])

const form = {
  code: null,
  name: null,
  name_latin: null,
  sex: null,
  position: null,
  dob: null,
  pob: null,
  address: null,
  phone: null,
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
      if (typeof fileReader.result === "string")
        additional_image.value = fileReader.result
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

    formData.append("code", formDataLocal.value.code)
    formData.append("name", formDataLocal.value.name)
    formData.append("sex", formDataLocal.value.sex)
    formData.append("position", formDataLocal.value.sex)
    if (formDataLocal.value.photo_path) {
      formData.append("photo_path", formDataLocal.value.photo_path)
    }
    if (formDataLocal.value.name_latin) {
      formData.append("name_latin", formDataLocal.value.name_latin)
    }
    if (formDataLocal.value.dob) {
      formData.append("dob", formDataLocal.value.dob)
    }
    if (formDataLocal.value.pob) {
      formData.append("pob", formDataLocal.value.pob)
    }
    if (formDataLocal.value.phone) {
      formData.append("phone", formDataLocal.value.phone)
    }
    if (formDataLocal.value.address) {
      formData.append("address", formDataLocal.value.address)
    }
    api
      .post("teachers-create", formData)
      .then(res => {
        router.push("/teacher")
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
      <VCard title="បង្កើតគ្រូបង្រៀន">
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
                <span class="d-none d-sm-block">Upload new photo</span>
              </VBtn>

              <input
                ref="refInputEl"
                type="file"
                name="file"
                accept=".jpeg,.png,.jpg,GIF"
                hidden
                @input="changeAvatar"
              >

              <VBtn
                type="reset"
                color="error"
                variant="tonal"
                @click="resetAvatar"
              >
                <span class="d-none d-sm-block">Reset</span>
                <VIcon
                  icon="mdi-refresh"
                  class="d-sm-none"
                />
              </VBtn>
            </div>

            <p class="text-body-1 mb-0">
              Allowed JPG, GIF or PNG. Max size of 800K
            </p>
          </div>
        </VCardText>

        <VDivider />

        <VCardText>
          <!-- 👉 Form -->
          <VForm
            ref="refForm"
            class="mt-6"
            lazy-validation
            @submit.prevent="submitHandler"
          >
            <VRow>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.code"
                  label="អត្ថលេខ"
                  :rules="[(v) => !!v || 'អត្ថលេខ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <!-- <VCol md="6" cols="12"></VCol> -->
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.name"
                  label="ឈ្មោះភាសាខ្មែរ"
                  :rules="[(v) => !!v || 'ឈ្មោះភាសាខ្មែរ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.name_latin"
                  label="ឈ្មោះឡាតាំង"
                />
              </VCol>

              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.dob"
                  label="ថ្ងៃខែឆ្នាំកំណើត"
                  type="date"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.pob"
                  label="ទីកន្លែងកំណើត"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="formDataLocal.phone"
                  label="លេខទូរសព្ទ"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VSelect
                  v-model="formDataLocal.sex"
                  :items="sexs"
                  item-title="name"
                  item-value="id"
                  label="ភេទ"
                  :rules="[(v) => !!v || 'ភេទ តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol
                md="6"
                cols="12"
              >
                <VSelect
                  v-model="formDataLocal.position"
                  :items="positions"
                  item-title="name"
                  item-value="id"
                  label="មុខដំណែង"
                  :rules="[(v) => !!v || 'មុខដំណែង តម្រូវឱ្យបំពេញ']"
                />
              </VCol>
              <VCol cols="12">
                <VTextarea
                  v-model="formDataLocal.address"
                  label="អាសយដ្ឋានបច្ចុប្បន្ន"
                  no-resize
                  rows="2"
                />
              </VCol>
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
                  រក្សាទុក
                </VBtn>

                <VBtn
                  color="secondary"
                  variant="tonal"
                  type="reset"
                  @click.prevent="resetForm"
                >
                  Reset
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
