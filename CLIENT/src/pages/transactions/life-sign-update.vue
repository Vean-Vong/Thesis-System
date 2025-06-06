<!-- eslint-disable import/extensions -->
<script setup>
// eslint-disable-next-line import/no-unresolved
import AppFormUpdateTemplate from '@/components/AppFormUpdateTemplate.vue'
// eslint-disable-next-line import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/no-unresolved
import router from '@/router'
import { ref } from 'vue'
import { onMounted } from 'vue'
import { reactive } from 'vue'

const form = reactive({
  data: {
    basic_info: {},
    life_sign: {},
    reason: '',
  },
  options: {
    sexs: [
      {
        id: 1,
        name: 'Female',
      },
      {
        id: 2,
        name: 'Male',
      },
    ],
  },
  additional: {},
})
const refForm = ref()
const submitting = ref(false)

const initData = async () => {
  const query = await router.currentRoute.value.query

  api.post('/patients-init-check', { id: query.uuid }).then(res => {
    form.data.basic_info = res.data.data.basic_info
    form.data.life_sign = res.data.data.life_sign
    form.data.reason = res.data.data.reason
  })
}

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    form.data.id = router.currentRoute.value.query.uuid
    api
      .post('/patients-edit', form.data)
      .then(res => {
        if (res.status == 200) router.push({ name: 'transactions-list1' })
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

onMounted(() => {
  initData()
})
</script>
<template>
  <AppFormUpdateTemplate
    cols="12"
    @submit="onUpdate"
    :title="$t('Update Life Sign')"
    :submitting="submitting"
  >
    <VForm
      ref="refForm"
      lazy-validation
    >
      <VRow>
        <VCol
          cols="12"
          md="12"
          offset-lg
        >
          <VRow>
            <VCol
              cols="12"
              md="6"
            >
              <VCard>
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Patient Info') }}</VCardTitle>

                <VCardText class="mt-5">
                  <VRow>
                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.basic_info.id_card"
                        :label="$t('id_card_no')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        id="name"
                        v-model="form.data.basic_info.name"
                        :label="$t('Name')"
                        required="true"
                        :rules="[v => !!v || $t('Name') + $t('required')]"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <AppSelectNew
                        id="sex"
                        v-model="form.data.basic_info.sex"
                        :label="$t('Sex')"
                        :items="form.options.sexs"
                        item-title="name"
                        item-value="id"
                        required="true"
                        :rules="[v => !!v || $t('Sex') + $t('required')]"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <AppTextField
                        id="age"
                        v-model="form.data.basic_info.age"
                        :label="$t('age')"
                        required="true"
                        :rules="[v => !!v || $t('age') + $t('required')]"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        id="phone"
                        v-model="form.data.basic_info.phone"
                        :label="$t('Phone')"
                      />
                    </VCol>
                  </VRow>
                </VCardText>
              </VCard>
            </VCol>
          </VRow>
          <VRow>
            <VCol
              cols="12"
              md="10"
            >
              <VCard class="mb-5 elevation-10">
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Life Sign') }} </VCardTitle>

                <VCardText class="mt-5">
                  <VRow>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.gcs"
                        :label="$t('GCS')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.bp"
                        :label="$t('BP')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.pulse"
                        :label="$t('Pulse')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <VLabel class="mb-1 text-body-2 text-high-emphasis"> {{ $t('T') }}<sup>o</sup> </VLabel>
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.to"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.rr"
                        :label="$t('RR')"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <VLabel class="mb-1 text-body-2 text-high-emphasis"> SpO<sub>2</sub> </VLabel>
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.spo2"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.fr"
                        :label="$t('ស្ករ F/R')"
                      />
                    </VCol>
                  </VRow>
                </VCardText>
              </VCard>
              <VRow>
                <VCol
                  cols="12"
                  md="12"
                >
                  <h4>{{ $t('Reason') }}</h4>
                  <AppTextField
                    id="id_card"
                    v-model="form.data.reason"
                  />
                </VCol>
              </VRow>
            </VCol>
          </VRow>
        </VCol>
      </VRow>
    </VForm>
  </AppFormUpdateTemplate>
</template>
