<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import constant from '@/constants'
// eslint-disable-next-line import/no-unresolved
import defaultImage from '@/assets/images/productImage/default.png'

const route = useRoute()
const router = useRouter()

const form = reactive({
  images: [null, null, null, null], // For new images
  previews: [defaultImage, defaultImage, defaultImage, defaultImage], // Preview URLs
  model: null,
  colors: null,
  filtration_stage: null,
  cold_water_tank_capacity: null,
  hot_water_tank_capacity: null,
  heating_capacity: null,
  cooling_capacity: null,
  cold_power_consumption: null,
  hot_power_consumption: null,
  quantity: 0,
  price: null,
  rental_price: null,
  install_price: null,
})

const refForm = ref()
const submitting = ref(false)

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
  integer: v => Number.isInteger(Number(v)) || 'ត្រូវតែជាលេខ',
}

// Fetch existing product details
const fetchProduct = async () => {
  if (!route.query.id) return
  try {
    const res = await api.get(`/products/${route.query.id}`)
    const data = res.data.data || {}

    // Populate form fields
    form.model = data.model
    form.colors = data.colors
    form.filtration_stage = data.filtration_stage
    form.cold_water_tank_capacity = data.cold_water_tank_capacity
    form.hot_water_tank_capacity = data.hot_water_tank_capacity
    form.heating_capacity = data.heating_capacity
    form.cooling_capacity = data.cooling_capacity
    form.cold_power_consumption = data.cold_power_consumption
    form.hot_power_consumption = data.hot_power_consumption
    form.quantity = data.quantity
    form.price = data.price
    form.rental_price = data.rental_price
    form.install_price = data.install_price

    // Populate existing images
    if (Array.isArray(data.images)) {
      data.images.forEach((img, i) => {
        if (img) {
          form.previews[i] = constant.storagePath + img
        }
      })
    }
  } catch (error) {
    console.error('Failed to fetch product:', error)
  }
}

onMounted(fetchProduct)

// Handle image selection & preview
function selectImage(index, event) {
  const file = event.target.files[0]
  if (file) {
    form.images[index] = file // Store new file
    form.previews[index] = URL.createObjectURL(file) // Show preview
  }
}

// Submit updated data
const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true

  try {
    const formData = new FormData()

    // Append images
    form.images.forEach((file, i) => {
      if (file) {
        formData.append(`images[${i}]`, file)
      }
    })

    // Append other fields
    formData.append('model', form.model)
    formData.append('colors', form.colors)
    formData.append('filtration_stage', form.filtration_stage)
    formData.append('cold_water_tank_capacity', form.cold_water_tank_capacity)
    formData.append('hot_water_tank_capacity', form.hot_water_tank_capacity)
    formData.append('heating_capacity', form.heating_capacity)
    formData.append('cooling_capacity', form.cooling_capacity)
    formData.append('cold_power_consumption', form.cold_power_consumption)
    formData.append('hot_power_consumption', form.hot_power_consumption)
    formData.append('quantity', form.quantity)
    formData.append('price', form.price)
    formData.append('rental_price', form.rental_price)
    formData.append('install_price', form.install_price)

    const response = await api.post(`/products/${route.query.id}?_method=PUT`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    if (response.status === 200) {
      router.push('/products')
    }
  } catch (error) {
    console.error('Error updating product:', error)
  } finally {
    submitting.value = false
  }
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
      lazy-validation
    >
      <!-- Multiple Images -->
      <VCol cols="12">
        <h5>ជ្រើសរើសរូបភាព</h5>
        <div class="image-grid">
          <div
            v-for="(preview, index) in form.previews"
            :key="index"
            class="image-option"
          >
            <img
              :src="preview"
              alt="Product Image"
              class="default-image"
              @click="$refs[`fileInput${index}`]?.click()"
            />
            <input
              :ref="`fileInput${index}`"
              type="file"
              accept="image/*"
              class="file-input"
              @change="event => selectImage(index, event)"
            />
          </div>
        </div>
      </VCol>

      <!-- Other Form Fields -->
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.model"
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
              'CABINET',
              'G-2000BA',
              'AQF-501',
              'FRO-0110',
            ]"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.colors"
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
            v-model="form.filtration_stage"
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
            v-model="form.cold_water_tank_capacity"
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
            v-model="form.hot_water_tank_capacity"
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
            v-model="form.heating_capacity"
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
            v-model="form.cooling_capacity"
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
            v-model="form.cold_power_consumption"
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
            v-model="form.hot_power_consumption"
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
            v-model="form.price"
            :label="$t('Price')"
            :rules="[rules.required, rules.integer]"
            outlined
            type="number"
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.rental_price"
            :label="$t('Rental Price')"
            :rules="[rules.numeric]"
            type="number"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.install_price"
            :label="$t('Install Price')"
            :rules="[rules.numeric]"
            type="number"
            outlined
          />
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<style scoped>
.image-grid {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.image-option {
  position: relative;
  width: 200px;
  height: 200px;
  border: 2px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 0.3s ease;
}

.default-image {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.file-input {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}
</style>

<route lang="yaml">
meta:
  title: Product Update
  layout: default
  subject: Auth
  active: 'product'
</route>
