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
      model: '',
      color: '',
      filtration_stage: null,
      cold_water_tank_capacity: '',
      hot_water_tank_capacity: '',
      heating_capacity: '',
      cooling_capacity: '',
      cold_power_consumption: '',
      hot_power_consumption: '',
      quantity: null,
    })

    const refForm = ref()
    const submitting = ref(false)

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
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.model"
            :label="$t('Model')"
            :rules="[rules.required]"
            :items="['GP-80B', 'G-6000C']"
            outlined
          />
        </VCol>
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.color"
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
            :items="['4-filters', '5-filter']"
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
            :items="['GP-80B', 'G-6000C']"
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
            :items="['GP-80B', 'G-6000C']"
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
            :items="['GP-80B', 'G-6000C']"
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
            :items="['GP-80B', 'G-6000C']"
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
            :items="['GP-80B', 'G-6000C']"
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
            :items="['GP-80B', 'G-6000C']"
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
        <VCol cols="4">
          <VFileInput
            :label="$t('Image')"
            outlined
            @change="e => (form.image = e.target.files[0])"
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
