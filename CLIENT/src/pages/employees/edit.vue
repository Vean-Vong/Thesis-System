<script setup>
import api from "@/plugins/utilites";
import router from "@/router";
const submitting = ref(false);
const refForm = ref();
const dialog = ref(false);
const form = reactive({
  data: {
    id_card_no: null,
    khmer_name: null,
    latin_name: null,
    sex: null,
    dob: null,
    phone: null,
    religion: "ព្រះពុទ្ធ",
    nationality: "ខ្មែរ",
    pob_address: null,
    status: null,
    home_no: null,
    street_no: null,
    group_no: null,
    position_id: null,
    join_date: null,
    facebook: null,
    email: null,
    address: null,
  },
  options: {
    positions: [],
    statuses: [
      {
        id: 1,
        name: "Active / Working",
      },
      {
        id: 0,
        name: "Inactive / Leave",
      },
    ],
    sexs: [
      {
        id: "1",
        name: "Female",
      },
      {
        id: "2",
        name: "Male",
      },
    ],
  },
  additional: {
    address: null,
  },
});

onMounted(async () => {
  const query = await router.currentRoute.value.query;
  await api.post("/employees-show", { id: query.uuid }).then((res) => {
    form.data = res.data.data;
  });

  if (form.data.address) await getAddressFull(form.data.address);

  await api.post("/employees-init").then((res) => {
    form.options.positions = res.data.data.positions;
  });
});

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    submitting.value = true;
    api
      .post("/employees-update", form.data)
      .then((res) => {
        if (res.status == 200) router.back();
      })
      .finally(() => {
        submitting.value = false;
      });
  }
};

const getaddress = (newAddress) => {
  form.data.address = newAddress.village_id;
  form.additional.address = newAddress.address;
};

const resetAddress = () => {
  form.data.address = null;
  form.additional.address = null;
};

const getAddressFull = (it) => {
  api.post("get-address-full", { village_id: it }).then((res) => {
    if (res.status == 200) {
      form.additional.address = res.data.data.address;
    }
  });
};
</script>

<template>
  <LocationFormDialog
    v-model="dialog"
    :dialog="dialog"
    :formText="form.data.pob_address"
    @get-address="getaddress"
  >
  </LocationFormDialog>
  <AppFormUpdateTemplate
    cols="12"
    @submit="onUpdate"
    :title="$t('Update Employee')"
    :submitting="submitting"
  >
    <VForm ref="refForm" lazy-validation>
      <VRow>
        <VCol cols="12" md="12" offset-lg>
          <VRow>
            <VCol cols="12">
              <VRow>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="id_card_no"
                    v-model="form.data.id_card_no"
                    :label="$t('id_card_no')"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="khmer_name"
                    v-model="form.data.khmer_name"
                    :label="$t('khmer_name')"
                    required="true"
                    :rules="[(v) => !!v || $t('khmer_name') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="latin_name"
                    v-model="form.data.latin_name"
                    :label="$t('latin_name')"
                    required="true"
                    :rules="[(v) => !!v || $t('latin_name') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="dob"
                    v-model="form.data.dob"
                    :label="$t('dob')"
                    required="true"
                    :rules="[(v) => !!v || $t('dob') + $t('required')]"
                    type="date"
                  />
                </VCol>
                <VCol cols="12" md="2">
                  <AppSelect
                    id="sex"
                    v-model="form.data.sex"
                    :label="$t('Sex')"
                    :items="form.options.sexs"
                    item-title="name"
                    item-value="id"
                    required="true"
                    :rules="[(v) => !!v || $t('Sex') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="2">
                  <AppTextField
                    id="phone"
                    v-model="form.data.phone"
                    :label="$t('Phone')"
                    required="true"
                    :rules="[(v) => !!v || $t('Phone') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="2">
                  <AppTextField
                    id="religion"
                    v-model="form.data.religion"
                    :label="$t('religion')"
                    required="true"
                    :rules="[(v) => !!v || $t('religion') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="1">
                  <AppTextField
                    id="nationality"
                    v-model="form.data.nationality"
                    :label="$t('nationality')"
                    required="true"
                    :rules="[(v) => !!v || $t('nationality') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="5">
                  <AppTextField
                    id="pob_address"
                    v-model="form.data.pob_address"
                    :label="$t('pob_address')"
                    required="true"
                    :rules="[(v) => !!v || $t('pob_address') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="2">
                  <AppSelect
                    id="status"
                    v-model="form.data.status"
                    :label="$t('status')"
                    :items="form.options.statuses"
                    item-title="name"
                    item-value="id"
                    required="true"
                    :rules="[(v) => !!v || $t('status') + $t('required')]"
                  />
                </VCol>
                <VCol cols="12" md="2">
                  <AppTextField
                    id="home_no"
                    v-model="form.data.home_no"
                    :label="$t('home_no')"
                  />
                </VCol>
                <VCol cols="12" md="2">
                  <AppTextField
                    id="street_no"
                    v-model="form.data.street_no"
                    :label="$t('street_no')"
                  />
                </VCol>
                <VCol cols="12" md="1">
                  <AppTextField
                    id="group_no"
                    v-model="form.data.group_no"
                    :label="$t('group_no')"
                  />
                </VCol>
                <VCol cols="12" md="2">
                  <AppSelect
                    :items="form.options.positions"
                    id="position_id"
                    item-title="latin_name"
                    item-value="id"
                    v-model="form.data.position_id"
                    :label="$t('Position')"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="join_date"
                    v-model="form.data.join_date"
                    :label="$t('join_date')"
                    type="date"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="facebook"
                    v-model="form.data.facebook"
                    :label="$t('facebook')"
                  />
                </VCol>
                <VCol cols="12" md="3">
                  <AppTextField
                    id="email"
                    v-model="form.data.email"
                    :label="$t('Email')"
                  />
                </VCol>
                <VCol cols="12" md="6" class="d-flex align-end gap-5">
                  <AppTextField
                    id="address"
                    v-model="form.additional.address"
                    :label="$t('Address Setting')"
                    readonly
                    @click="dialog = !dialog"
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

<route lang="yaml">
meta:
  title: Employee Update
  layout: default
  subject: Auth
  active: "employee"
</route>
