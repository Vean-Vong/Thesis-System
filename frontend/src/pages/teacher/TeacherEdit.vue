<template>
  <div class="q-mx-sm">
    <div class="q-pa-md">
      <div
        class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize"
      >
        Edit Teacher
      </div>
      <br />
      <q-card>
        <div class="flex justify-start items-center q-pt-md q-px-md">
          <q-btn
            :to="{ name: 'teacher.index' }"
            class="q-pr-sm"
            no-caps
            outline
            color="primary"
            dense
          >
            <q-icon name="arrow_left" />
            Back
          </q-btn>
        </div>
        <div>
          <ValidateForm ref="form_ref" :validation-schema="rules">
            <form @submit.prevent="onSubmit">
              <div class="row justify-center items-center">
                <div
                  class="q-px-md q-pt-lg col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-grey-8"
                >
                  <!-- <fieldset class="fieldset-p">
                      <legend class="legend q-px-sm text-capitalize">
                        personal infomation
                      </legend> -->
                  <div class="row justify-center items-center q-pb-md">
                    <!-- <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="flex justify-center">
                            <img
                              class="image-in-q-avatar"
                              src="https://i.pinimg.com/550x/9a/e5/c2/9ae5c20f9349e872f05e9feead42b64e.jpg"
                              alt="no image"
                            />
                          </div>
                        </div> -->
                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="flex justify-center">
                        <!-- <div class="row flex justify-center">
                              <q-input
                                ref="handlingFile"
                                style="display: none"
                                v-model="form.photo_path"
                                type="file"
                                label="Standard"
                              ></q-input>
                              <q-btn
                                no-caps
                                color="primary"
                                unelevated
                                label="Upload Picture"
                                icon="cloud_upload"
                                @click="getFile"
                              ></q-btn>
                            </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="row justify-center items-center self-center">
                    <div
                      class="q-px-sm q-pb-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12"
                    >
                      <div>
                        <ValidateField
                          name="last_name"
                          v-slot="{ value, field, errorMessage }"
                          v-model="form.last_name"
                        >
                          <q-input
                            :model-value="value"
                            v-bind="field"
                            :error="!!errorMessage"
                            outlined
                            dense
                            :error-message="errorMessage"
                            v-model="form.last_name"
                            label="Last name"
                          ></q-input>
                        </ValidateField>
                      </div>
                    </div>
                    <div
                      class="q-px-sm q-pb-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12"
                    >
                      <div>
                        <ValidateField
                          name="first_name"
                          v-model="form.first_name"
                          v-slot="{ value, field, errorMessage }"
                        >
                          <q-input
                            :model-value="value"
                            v-bind="field"
                            :error="!!errorMessage"
                            :error-message="errorMessage"
                            outlined
                            dense
                            v-model="form.first_name"
                            label="First name"
                          ></q-input>
                        </ValidateField>
                      </div>
                    </div>
                    <div
                      class="q-px-sm q-pb-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12"
                    >
                      <div>
                        <ValidateField
                          name="last_name_latin"
                          v-model="form.last_name_latin"
                          v-slot="{ value, field, errorMessage }"
                        >
                          <q-input
                            :model-value="value"
                            v-bind="field"
                            :error="!!errorMessage"
                            :error-message="errorMessage"
                            outlined
                            dense
                            v-model="form.last_name_latin"
                            label="Last name latin"
                          ></q-input>
                        </ValidateField>
                      </div>
                    </div>
                    <div
                      class="q-px-sm q-pb-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12"
                    >
                      <div>
                        <ValidateField
                          name="first_name_latin"
                          v-model="form.first_name_latin"
                          v-slot="{ value, field, errorMessage }"
                        >
                          <q-input
                            :model-value="value"
                            v-bind="field"
                            :error="!!errorMessage"
                            :error-message="errorMessage"
                            outlined
                            dense
                            v-model="form.first_name_latin"
                            label="First name latin"
                          ></q-input>
                        </ValidateField>
                      </div>
                    </div>
                    <div class="q-px-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <ValidateField
                          name="code"
                          v-model="form.code"
                          v-slot="{ value, field, errorMessage }"
                        >
                          <q-input
                            :model-value="value"
                            v-bind="field"
                            :error="!!errorMessage"
                            :error-message="errorMessage"
                            outlined
                            dense
                            v-model="form.code"
                            label="Code"
                          ></q-input>
                        </ValidateField>
                      </div>
                    </div>
                    <div class="q-px-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <ValidateField
                          name="gender"
                          v-model="form.gender"
                          v-slot="{ value, field, errorMessage }"
                        >
                          <q-select
                            v-model="form.gender"
                            :model-value="value"
                            :error="!!errorMessage"
                            v-bind="field"
                            :error-message="errorMessage"
                            option-label="name"
                            option-value="id"
                            emit-value
                            map-options
                            outlined
                            dense
                            :options="options_gender"
                            label="gender"
                          />
                        </ValidateField>
                      </div>
                    </div>
                    <div class="q-px-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <ValidateField
                          name="dob"
                          v-model="form.dob"
                          v-slot="{ value, field, errorMessage }"
                        >
                          <q-input
                            label="Day of birth"
                            :model-value="value"
                            v-bind="field"
                            :error-message="errorMessage"
                            :error="!!errorMessage"
                            v-model="form.dob"
                            dense
                            outlined
                            mask="date"
                          >
                            <template v-slot:append>
                              <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy
                                  cover
                                  transition-show="scale"
                                  transition-hide="scale"
                                >
                                  <q-date default-view="Years" v-model="form.dob">
                                    <div class="row items-center justify-end">
                                      <q-btn
                                        dense
                                        v-close-popup
                                        label="Close"
                                        color="primary"
                                        flat
                                      />
                                    </div>
                                  </q-date>
                                </q-popup-proxy>
                              </q-icon>
                            </template>
                          </q-input>
                        </ValidateField>
                      </div>
                    </div>
                    <div class="q-px-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <ValidateField
                          name="phone"
                          v-model="form.phone"
                          v-slot="{ value, field, errorMessage }"
                        >
                          <q-input
                            :model-value="value"
                            :error="!!errorMessage"
                            :error-message="errorMessage"
                            v-bind="field"
                            v-model="form.phone"
                            outlined
                            dense
                            label="Phone number"
                          ></q-input>
                        </ValidateField>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="row q-px-sm justify-center items-center">
                        <div class="col-12 col-lg-1 col-md-2 col-sm-3 col-xs-12">
                          Address
                        </div>
                        <div class="col-12 col-lg-11 col-md-10 col-sm-9 col-xs-12">
                          <div class="ruler"></div>
                        </div>
                      </div> -->

                  <div class="row justify-center items-center">
                    <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <ValidateField
                        name="village"
                        v-model="form.village"
                        v-slot="{ value, field, errorMessage }"
                      >
                        <q-input
                          v-model="form.village"
                          :error="!!errorMessage"
                          :error-message="errorMessage"
                          :model-value="value"
                          v-bind="field"
                          outlined
                          dense
                          label="Village"
                        ></q-input>
                      </ValidateField>
                    </div>
                    <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <ValidateField
                        name="commune"
                        v-model="form.commune"
                        v-slot="{ value, field, errorMessage }"
                      >
                        <q-input
                          :error="!!errorMessage"
                          :model-value="value"
                          v-bind="field"
                          :error-message="errorMessage"
                          v-model="form.commune"
                          outlined
                          dense
                          label="Commune"
                        ></q-input>
                      </ValidateField>
                    </div>
                    <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <ValidateField
                        name="district"
                        v-model="form.district"
                        v-slot="{ value, field, errorMessage }"
                      >
                        <q-input
                          :model-value="value"
                          :error="!!errorMessage"
                          v-bind="field"
                          :error-message="errorMessage"
                          v-model="form.district"
                          outlined
                          dense
                          label="District"
                        ></q-input>
                      </ValidateField>
                    </div>
                    <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <ValidateField
                        name="province"
                        v-model="form.province"
                        v-slot="{ value, field, errorMessage }"
                      >
                        <q-input
                          :error="!!errorMessage"
                          :error-message="errorMessage"
                          :model-value="value"
                          v-bind="field"
                          v-model="form.province"
                          outlined
                          dense
                          label="Province"
                        ></q-input>
                      </ValidateField>
                    </div>
                  </div>
                  <div class="row justify-start items-start">
                    <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <ValidateField
                        name="school_name"
                        v-model="form.school_name"
                        v-slot="{ value, field, errorMessage }"
                      >
                        <q-input
                          v-model="form.school_name"
                          :error="!!errorMessage"
                          :error-message="errorMessage"
                          :model-value="value"
                          v-bind="field"
                          outlined
                          dense
                          label="School name"
                        ></q-input>
                      </ValidateField>
                    </div>
                    <div class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                      <ValidateField
                        name="school_code"
                        v-model="form.school_code"
                        v-slot="{ value, field, errorMessage }"
                      >
                        <q-input
                          :error="!!errorMessage"
                          :model-value="value"
                          v-bind="field"
                          :error-message="errorMessage"
                          v-model="form.school_code"
                          outlined
                          dense
                          label="School Code"
                        ></q-input>
                      </ValidateField>
                    </div>
                  </div>
                  <div class="row justify-end items-end">
                    <div class="q-pa-sm col-12 col-lg-2 col-md-1 col-sm-12 col-xs-12">
                      <q-toggle
                        :label="`${form.status == true ? 'Teaching' : 'Retired'}`"
                        v-model="form.status"
                      />
                    </div>
                  </div>
                  <!-- </fieldset> -->
                </div>
              </div>
              <div class="flex justify-end items-center q-py-md q-px-md">
                <q-btn
                  :loading="form.submitting"
                  unelevated
                  dense
                  type="submit"
                  class="q-px-sm"
                  color="primary"
                >
                  create
                </q-btn>
              </div>
            </form>
          </ValidateForm>
        </div>
      </q-card>
    </div>
  </div>
</template>

<script>
import store from "../../store";
import axiosApiInstance from "../../utilites";
import { mapGetters } from "vuex";
import { Form as ValidateForm, Field as ValidateField } from "vee-validate";
import { array, number, object, string } from "yup";
import dayjs from "dayjs";
export default {
  components: {
    ValidateField,
    ValidateForm,
  },
  data: () => ({
    axiosApiInstance: axiosApiInstance,
    form_ref: null,
    options_gender: [
      { id: 1, name: "Male" },
      { id: 2, name: "Female" },
    ],
    form: {
      photo_path: "",
      first_name: "",
      last_name: "",
      first_name_latin: "",
      last_name_latin: "",
      code: "",
      gender: "",
      dob: dayjs(new Date(new Date().setFullYear(new Date().getFullYear() - 30))).format(
        "YYYY/MM/DD"
      ),
      school_name: "",
      school_code: "",
      phone: "",
      village: "",
      commune: "",
      district: "",
      province: "",
      status: true,
      submitting: false,
    },
  }),
  methods: {
    getFile() {
      this.$refs.handlingFile.$el.click();
    },
    fetchData() {
      this.loading = true;
      axiosApiInstance
        .get(`/teachers/${this.$route.params.teacher}/edit`)
        .then((response) => {
          this.form = response.data.data;
          if (this.form.status == 1) {
            this.form.status = true;
          } else {
            this.form.status = false;
          }
        })
        .catch((response) => {
          this.$store.dispatch("pushNotification", {
            type: "error",
            message: response.response.data.message,
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    async onSubmit() {
      let { valid } = await this.$refs.form_ref.validate();
      if (valid) {
        if (this.form.status == true) {
          this.form.status = 1;
        } else {
          this.form.status = 2;
        }
        this.form.submitting = true;
        this.axiosApiInstance
          .put("/teachers/" + this.$route.params.teacher, this.form)
          .then((res) => {
            this.$store.dispatch("pushNotification", {
              type: "success",
              message: res.data.message,
            });
            this.$router.go(-1);
          })
          .catch((err) => {
            this.$store.dispatch("pushNotification", {
              type: "error",
              message: err.data.data.message,
            });
          })
          .finally(() => {
            this.form.submitting = false;
          });
      }
    },
    resetForm() {
      this.form.dob = dayjs(
        new Date(new Date().setFullYear(new Date().getFullYear() - 5))
      ).format("YYYY/MM/DD");
    },
  },
  computed: {
    rules() {
      return object({
        last_name: string().required().label("Last name"),
        first_name: string().required().label("First name"),
        last_name_latin: string().required().label("Last name latin"),
        first_name_latin: string().required().label("First name latin"),
        code: string().required().label("Code"),
        school_name: string().required().label("School name"),
        school_code: string().required().label("School code"),
        gender: string().required().label("Gender"),
        dob: string().required().label("Day of birth"),
        village: string().required().label("Village"),
        commune: string().required().label("Commune"),
        district: string().required().label("District"),
        province: string().required().label("Province"),
      });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
.fieldset-p {
  border-radius: 12px;
  border: 3px solid #9ca3af;
}

.ruler {
  border: #e5e7eb solid 2px;
  border-radius: 10px;
}

/* #1976d2 */
/* this is defualt color primary of quasar */
.legend {
  letter-spacing: 1px;
  background-color: #9ca3af;
  border-radius: 100px;
  color: white;
}

.image-in-q-avatar {
  object-fit: cover;
  width: 120px;
  height: 120px;
  border-radius: 100%;
  border: 3px solid #737373;
}
</style>
