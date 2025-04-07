<script setup>
import api from '@/plugins/utilites'
import router from '@/router'
const refForm = ref()
const submitting = ref(false)

const form = reactive({
  data: {
    id: null,
    basic_info: {},
    life_sign: {},
    reason: '',
    type1: [{ description: '' }, { description: '' }, { description: '' }],
    type2: [{ description: '' }, { description: '' }, { description: '' }],
    type3: [{ description: '' }, { description: '' }, { description: '' }],
    type4: [{ description: '' }, { description: '' }, { description: '' }],
    type5: [{ description: '' }, { description: '' }, { description: '' }, { description: '' }, { description: '' }],
    next_appointment_date: '',
    objective: '',
    total_service: null,
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

onMounted(async () => {
  const query = await router.currentRoute.value.query.id
  if (query) {
    api.post('/patients-init-check', { id: query }).then(res => {
      form.data.basic_info = res.data.data.basic_info
      form.data.life_sign = res.data.data.life_sign
      form.data.reason = res.data.data.reason
      form.data.id = res.data.data.id
      if (res.data.data.type1.length > 0) form.data.type1 = res.data.data.type1
      if (res.data.data.type2.length > 0) form.data.type2 = res.data.data.type2
      if (res.data.data.type3.length > 0) form.data.type3 = res.data.data.type3
      if (res.data.data.type4.length > 0) form.data.type4 = res.data.data.type4
      if (res.data.data.type5.length > 0) form.data.type5 = res.data.data.type5
    })
  }
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    submitting.value = true
    api
      .post('/patients-save-check', form.data)
      .then(res => {
        if (res.status == 200) router.push({ name: 'transactions-list2' })
      })
      .finally(() => {
        submitting.value = false
      })
  }
}

const onClickType1Add = () => {
  form.data.type1.push({
    description: '',
  })
}
const onClickType1Remove = index => {
  form.data.type1 = form.data.type1.filter(function (v, k) {
    return k != index
  })
}
const onClickType2Add = () => {
  form.data.type2.push({
    description: '',
  })
}
const onClickType2Remove = index => {
  form.data.type2 = form.data.type2.filter(function (v, k) {
    return k != index
  })
}
const onClickType3Add = () => {
  form.data.type3.push({
    description: '',
  })
}
const onClickType3Remove = index => {
  form.data.type3 = form.data.type3.filter(function (v, k) {
    return k != index
  })
}
const onClickType4Add = () => {
  form.data.type4.push({
    description: '',
  })
}
const onClickType4Remove = index => {
  form.data.type4 = form.data.type4.filter(function (v, k) {
    return k != index
  })
}
const onClickType5Add = () => {
  form.data.type5.push({
    description: '',
  })
}
const onClickType5Remove = index => {
  form.data.type5 = form.data.type5.filter(function (v, k) {
    return k != index
  })
}
</script>

<template>
  <AppFormCreateTemplate
    cols="12"
    @submit="onCreate"
    :title="$t('Create New Check')"
    :submitting="submitting"
    btnSubmit="Save"
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
            <VCol cols="12">
              <VCard
                class="my-5"
                elevation="1"
                flat
              >
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
                        readonly
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
                        readonly
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
                        readonly
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
                        readonly
                        :rules="[v => !!v || $t('age') + $t('required')]"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        id="phone"
                        readonly
                        v-model="form.data.basic_info.phone"
                        :label="$t('Phone')"
                      />
                    </VCol> </VRow
                ></VCardText>
              </VCard>
              <VCard
                class="my-5"
                elevation="1"
                flat
              >
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Life Sign') }}</VCardTitle>

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
                        readonly
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
                        readonly
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
                        readonly
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <VLabel class="mb-1 text-body-2 text-high-emphasis"> {{ $t('T') }}<sup>o</sup> </VLabel>
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.gcs"
                        readonly
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.gcs"
                        :label="$t('RR')"
                        readonly
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="3"
                    >
                      <VLabel class="mb-1 text-body-2 text-high-emphasis"> SpO<sub>2</sub> </VLabel>
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.gcs"
                        readonly
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      md="6"
                    >
                      <AppTextField
                        id="id_card"
                        v-model="form.data.life_sign.gcs"
                        :label="$t('ស្ករ F/R')"
                        readonly
                      />
                    </VCol> </VRow
                ></VCardText>
              </VCard>
              <VRow>
                <VCol
                  cols="12"
                  md="10"
                >
                  <AppTextField
                    id="id_card"
                    readonly
                    v-model="form.data.reason"
                    :label="$t('Reason')"
                  />
                </VCol>
              </VRow>
              <VCard
                class="my-5"
                elevation="1"
                flat
              >
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Body Check') }}</VCardTitle>
                <VCardText class="mt-5">
                  <v-row
                    v-for="(ret, index) in form.data.type1"
                    :key="index"
                  >
                    <v-col
                      class="py-0"
                      cols="12"
                      md="10"
                      lg="10"
                      sm="12"
                    >
                      <AppTextField
                        id="description"
                        v-model="ret.description"
                        :label="$t('Description') + ' ' + (Number(index) + 1)"
                      />
                    </v-col>
                    <v-col
                      class="py-0 mt-3"
                      cols="12"
                      md="2"
                      lg="2"
                      sm="12"
                    >
                      <v-btn
                        v-if="index > 0"
                        @click="onClickType1Remove(index)"
                        icon
                        size="large"
                        color="warning"
                        variant="plain"
                      >
                        <v-icon>mdi-trash-can-outline</v-icon>
                      </v-btn>
                      <v-btn
                        v-if="index == form.data.type1.length - 1"
                        @click="onClickType1Add"
                        icon
                        color="primary"
                        variant="plain"
                        size="large"
                      >
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                </VCardText>
              </VCard>
              <VCard
                class="my-5"
                elevation="1"
                flat
              >
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Clinical Diagnosis') }}</VCardTitle>

                <VCardText class="mt-5">
                  <v-row
                    v-for="(ret, index) in form.data.type2"
                    :key="index"
                  >
                    <v-col
                      class="py-0"
                      cols="12"
                      md="10"
                      lg="10"
                      sm="12"
                    >
                      <AppTextField
                        id="description"
                        v-model="ret.description"
                        :label="$t('Description') + ' ' + (Number(index) + 1)"
                      />
                    </v-col>
                    <v-col
                      class="py-0 mt-3"
                      cols="12"
                      md="2"
                      lg="2"
                      sm="12"
                    >
                      <v-btn
                        v-if="index > 0"
                        @click="onClickType2Remove(index)"
                        icon
                        size="large"
                        color="warning"
                        variant="plain"
                      >
                        <v-icon>mdi-trash-can-outline</v-icon>
                      </v-btn>
                      <v-btn
                        v-if="index == form.data.type2.length - 1"
                        @click="onClickType2Add"
                        icon
                        color="primary"
                        variant="plain"
                        size="large"
                      >
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                </VCardText>
              </VCard>
              <VCard
                class="my-5"
                elevation="1"
                flat
              >
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Request an Analysis') }}</VCardTitle>

                <VCardText class="mt-5">
                  <v-row
                    v-for="(ret, index) in form.data.type3"
                    :key="index"
                  >
                    <v-col
                      class="py-0"
                      cols="12"
                      md="10"
                      lg="10"
                      sm="12"
                    >
                      <AppTextField
                        id="description"
                        v-model="ret.description"
                        :label="$t('Description') + ' ' + (Number(index) + 1)"
                      />
                    </v-col>
                    <v-col
                      class="py-0 mt-3"
                      cols="12"
                      md="2"
                      lg="2"
                      sm="12"
                    >
                      <v-btn
                        v-if="index > 0"
                        @click="onClickType3Remove(index)"
                        icon
                        size="large"
                        color="warning"
                        variant="plain"
                      >
                        <v-icon>mdi-trash-can-outline</v-icon>
                      </v-btn>
                      <v-btn
                        v-if="index == form.data.type3.length - 1"
                        @click="onClickType3Add"
                        icon
                        color="primary"
                        variant="plain"
                        size="large"
                      >
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                </VCardText>
              </VCard>
              <VCard
                class="my-5"
                elevation="1"
                flat
              >
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Diagnosis') }}</VCardTitle>

                <VCardText class="mt-5">
                  <v-row
                    v-for="(ret, index) in form.data.type4"
                    :key="index"
                  >
                    <v-col
                      class="py-0"
                      cols="12"
                      md="10"
                      lg="10"
                      sm="12"
                    >
                      <AppTextField
                        id="description"
                        v-model="ret.description"
                        :label="$t('Description') + ' ' + (Number(index) + 1)"
                      />
                    </v-col>
                    <v-col
                      class="py-0 mt-3"
                      cols="12"
                      md="2"
                      lg="2"
                      sm="12"
                    >
                      <v-btn
                        v-if="index > 0"
                        @click="onClickType4Remove(index)"
                        icon
                        size="large"
                        color="warning"
                        variant="plain"
                      >
                        <v-icon>mdi-trash-can-outline</v-icon>
                      </v-btn>
                      <v-btn
                        v-if="index == form.data.type4.length - 1"
                        @click="onClickType4Add"
                        icon
                        color="primary"
                        variant="plain"
                        size="large"
                      >
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                </VCardText>
              </VCard>
              <VCard
                class="my-5"
                elevation="1"
                flat
              >
                <VCardTitle class="bg-grey-200 text-subtitle-1">{{ $t('Treatment') }}</VCardTitle>

                <VCardText class="mt-5">
                  <v-row
                    v-for="(ret, index) in form.data.type5"
                    :key="index"
                  >
                    <v-col
                      class="py-0"
                      cols="12"
                      md="10"
                      lg="10"
                      sm="12"
                    >
                      <AppTextField
                        id="description"
                        v-model="ret.description"
                        :label="$t('Description') + ' ' + (Number(index) + 1)"
                      />
                    </v-col>
                    <v-col
                      class="py-0 mt-3"
                      cols="12"
                      md="2"
                      lg="2"
                      sm="12"
                    >
                      <v-btn
                        v-if="index > 0"
                        @click="onClickType5Remove(index)"
                        icon
                        size="large"
                        color="warning"
                        variant="plain"
                      >
                        <v-icon>mdi-trash-can-outline</v-icon>
                      </v-btn>
                      <v-btn
                        v-if="index == form.data.type5.length - 1"
                        @click="onClickType5Add"
                        icon
                        color="primary"
                        variant="plain"
                        size="large"
                      >
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                </VCardText>
              </VCard>
              <VRow class="mx-auto">
                <VCol cols="1"></VCol>
                <VCol
                  cols="12"
                  md="5"
                >
                  <AppTextField
                    id="id_card"
                    v-model="form.data.next_appointment_date"
                    :label="$t('Next Appointment Date')"
                    type="date"
                  />
                </VCol>
                <VCol
                  cols="12"
                  md="5"
                >
                  <AppTextField
                    id="id_card"
                    v-model="form.data.objective"
                    :label="$t('Objective')"
                  />
                </VCol>
                <VCol
                  cols="1"
                  class="ma-0 pa-0"
                ></VCol>
                <VCol
                  cols="1"
                  class="ma-0 pa-0"
                ></VCol>
                <VCol
                  cols="12"
                  md="10"
                >
                  <AppTextField
                    id="id_card"
                    v-model="form.data.total_service"
                    :label="$t('Total Services')"
                  />
                </VCol>
                <VCol cols="1"></VCol>
              </VRow>
            </VCol>
          </VRow>
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Patient Create
  layout: default
  subject: Auth
  active: 'transaction'
</route>
