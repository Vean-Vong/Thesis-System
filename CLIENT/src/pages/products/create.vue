<script>
import { reactive, ref, onMounted } from 'vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'

export default {
  setup() {
    const form = reactive({
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
    })

    const refForm = ref()
    const submitting = ref(false)
    const previewImage = ref(null) // to store the image preview URL

    const onCreate = async () => {
      const { valid } = await refForm.value?.validate()
      if (valid) {
        submitting.value = true
        const formData = new FormData()

        Object.keys(form).forEach(key => {
          formData.append(key, form[key])
        })

        api
          .post('/products', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          .then(res => {
            if (res.status === 200) {
              router.push('/products')
            }
          })
          .catch(error => {
            console.error('Error creating product:', error)
          })
          .finally(() => {
            submitting.value = false
          })
      }
    }

    // Handle image selection and set the preview
    const handleImageChange = event => {
      const file = event.target.files[0]
      if (file) {
        form.image = file
        previewImage.value = URL.createObjectURL(file)
      }
    }

    const rules = {
      required: v => !!v || 'This field is required',
      integer: v => Number.isInteger(Number(v)) || 'Must be an integer',
    }

    return {
      form,
      refForm,
      submitting,
      onCreate,
      rules,
      previewImage, // expose the previewImage ref
      handleImageChange, // expose the handleImageChange function
    }
  },
}
</script>

<template>
  <AppFormCreateTemplate
    cols="9"
    :title="$t('Create New Customer')"
    :submitting="submitting"
    @submit="onCreate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onCreate"
    >
      <VCol cols="12">
        <div class="mb-4">
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
            :items="['Black', 'White']"
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
            v-model="form.quantity"
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
  title: Product Create
  layout: default
  subject: Auth
  active: 'product'
</route>
