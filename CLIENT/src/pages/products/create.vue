<script setup>
import { reactive, ref } from 'vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites' // your axios instance or similar
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'
// eslint-disable-next-line import/no-unresolved
import defaultImage from '@/assets/images/productImage/default.png'

// Form reactive state
const form = reactive({
  images: [null, null, null, null],
  previews: [defaultImage, defaultImage, defaultImage, defaultImage],
  model: null,
  colors: null,
  price: null,
  rental_price: null,
  install_price: null,
  filtration_stage: null,
  cold_water_tank_capacity: null,
  hot_water_tank_capacity: null,
  heating_capacity: null,
  cooling_capacity: null,
  cold_power_consumption: null,
  hot_power_consumption: null,
  quantity: 0,
})

const refForm = ref(null)
const submitting = ref(false)

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
  numeric: v => !v || !isNaN(v) || 'ត្រូវតែជាលេខ',
}

// Handle image selection & preview
function selectImage(index, event) {
  const file = event.target.files[0]
  if (file) {
    form.images[index] = file
    form.previews[index] = URL.createObjectURL(file)
  }
}

async function onCreate() {
  // Validate form first (assuming Vuetify VForm)
  const valid = await refForm.value.validate()
  if (!valid) return

  submitting.value = true

  try {
    const formData = new FormData()
    form.images.forEach((file, i) => {
      if (file) {
        formData.append(`images[${i}]`, file)
      }
    })

    // Append other form fields
    formData.append('model', form.model)
    formData.append('colors', form.colors)
    formData.append('price', form.price)
    formData.append('filtration_stage', form.filtration_stage)
    formData.append('cold_water_tank_capacity', form.cold_water_tank_capacity)
    formData.append('hot_water_tank_capacity', form.hot_water_tank_capacity)
    formData.append('heating_capacity', form.heating_capacity)
    formData.append('cooling_capacity', form.cooling_capacity)
    formData.append('cold_power_consumption', form.cold_power_consumption)
    formData.append('hot_power_consumption', form.hot_power_consumption)
    formData.append('quantity', form.quantity)
    formData.append('rental_price', form.rental_price || 0)
    formData.append('install_price', form.install_price || 0)

    // Send POST request
    const response = await api.post('/products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    if (response.status === 200) {
      router.push('/products')
    }
  } catch (error) {
    console.error('Error creating product:', error)
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Create New Product')"
    :submitting="submitting"
    @submit="onCreate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onCreate"
      lazy-validation
    >
      <VCol cols="12">
        <h5 class="select_images">ជ្រើសរើសរូបភាព</h5>
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
            :items="['Black', 'White', 'silver', 'red', 'grey']"
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
            :rules="[rules.required, rules.numeric]"
            type="number"
            outlined
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
          <VTextField
            v-model="form.install_price"
            :label="$t('Install Price')"
            :rules="[rules.numeric]"
            type="number"
            outlined
          />
        </VCol>
        <!-- Add remaining fields similarly -->

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
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<style scoped>
.image-grid {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  justify-content: start;
}

.image-option {
  position: relative;
  border: 2px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 0.3s ease;
  width: 200px;
  height: 200px;
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
