<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import constant from '@/constants'

const route = useRoute()
const router = useRouter()

const form = reactive({
  data: {
    image: null,
    model: null,
    colors: null,
    filtration_stage: null,
    cold_water_tank_capacity: null,
    hot_water_tank_capacity: null,
    heating_capacity: null,
    cooling_capacity: null,
    cold_power_consumption: null,
    hot_power_consumption: null,
    quantity: null,
  },
})

const refForm = ref()
const submitting = ref(false)
const previewImage = ref(null)

const fetchProduct = async () => {
  if (!route.query.id) return
  try {
    const res = await api.get(`/products/${route.query.id}`)
    const data = res.data.data || {}
    form.data = { ...data }
    if (data.image) {
      previewImage.value = constant.storagePath + data.image
    }
  } catch (error) {
    console.error('Failed to fetch product:', error)
  }
}

onMounted(() => {
  fetchProduct()
})

const handleImageChange = event => {
  const file = event.target.files[0]
  if (file) {
    form.data.image = file
    previewImage.value = URL.createObjectURL(file)
  }
}

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    try {
      const formData = new FormData()

      // Append the form data
      for (const key in form.data) {
        if (key === 'image' && form.data.image instanceof File) {
          formData.append('image', form.data.image)
        } else {
          formData.append(key, form.data[key])
        }
      }

      const res = await api.post(`/products/${route.query.id}?_method=PUT`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })

      if (res.status === 200) {
        router.back()
      }
    } catch (error) {
      console.error('Error updating product:', error)
    } finally {
      submitting.value = false
    }
  }
}

const rules = {
  required: v => !!v || 'This field is required',
  integer: v => Number.isInteger(Number(v)) || 'Must be an integer',
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Update Product')"
    :submitting="submitting"
    @submit="onUpdate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onUpdate"
    >
      <VRow>
        <VCol cols="12">
          <div>
            <div v-if="previewImage">
              <img
                :src="previewImage"
                alt="Preview"
                style="width: 200px"
              >
            </div>
          </div>
          <input
            type="file"
            accept="image/*"
            @change="handleImageChange"
          >
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.model"
            :label="$t('Model')"
            :rules="[rules.required]"
            :items="[
              'GP-80B',
              'GP-900',
              'GP-50',
              'G-6000C',
              'GP-900S',
              'GP-500S',
              'GP-80S',
              'GP-700S',
              'Maxtream',
              'Under-Sink-Case',
            ]"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.colors"
            :label="$t('Color')"
            :rules="[rules.required]"
            :items="['Black', 'White', 'Red', 'Blue']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.filtration_stage"
            :label="$t('Filter')"
            :rules="[rules.required]"
            :items="['4-filters', '5-filters']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.cold_water_tank_capacity"
            :label="$t('Cold water Tank Capacity')"
            :rules="[rules.required]"
            :items="['8L', '3L', '3.5L', '5L', '7.5L']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.hot_water_tank_capacity"
            :label="$t('Hot water Tank Capacity')"
            :rules="[rules.required]"
            :items="['3L', '1.25L', '2.15L', '5L']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.heating_capacity"
            :label="$t('Heating Capacity')"
            :rules="[rules.required]"
            :items="['80C-90C']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.cooling_capacity"
            :label="$t('Cooling Capacity')"
            :rules="[rules.required]"
            :items="['4C-10C']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.cold_power_consumption"
            :label="$t('Cold Power Consumption')"
            :rules="[rules.required]"
            :items="['100W', '110W']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.hot_power_consumption"
            :label="$t('Hot Power Consumption')"
            :rules="[rules.required]"
            :items="['300W', '430W']"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.data.quantity"
            :label="$t('Quantity')"
            :rules="[rules.required, rules.integer]"
            outlined
            type="number"
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Product Update
  layout: default
  subject: Auth
  active: 'product'
</route>
