<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 rounded-borders q-px-sm q-py-xs text-bold text-h6 text-capitalize"
        >
          Create Role and Permission
        </div>
        <br />
        <q-card>
          <div class="q-px-md q-py-md">
            <div class="q-mb-md">
              <q-btn
                :to="{ name: 'role.index' }"
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
            <div class="row flex justify-center">
              <div class="col-12 col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <div class="q-pt-sm q-px-md">
                  <div>
                    <form action="">
                      <div class="q-mb-sm">
                        <q-input
                          label="Name"
                          outlined
                          dense
                          v-model="form.name"
                        ></q-input>
                      </div>
                      <div class="text-red">{{ name }}</div>
                      <div class="q-mt-sm">
                        <q-input
                          label="Display Name"
                          class="text-capitalize"
                          v-model="form.display_name"
                          outlined
                          dense
                        ></q-input>
                      </div>
                      <div class="text-red q-mt-sm">{{ display_name }}</div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-12 col-xl-8 col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="q-pt-sm q-pb-sm">
                  <div>
                    <q-card v-if="loading">
                      <!-- <div
                        class="text-bold text-white q-px-sm bg-amber-10 text-capitalize"
                      >
                        select permission
                      </div> -->
                      <div class="q-pa-md flex justify-center">
                        <fieldset class="fieldset-r">
                          <legend class="q-pr-sm">Select permission</legend>
                          <div class="row flex justify-center items-center">
                            <div
                              class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-12"
                              v-for="permission in permissionOpt"
                              :key="permission.id"
                            >
                              <q-checkbox
                                v-model="form.permission_ids"
                                :key="permission.id"
                                :val="permission.id"
                                :label="permission.display_name"
                              />
                            </div>
                          </div>
                        </fieldset>
                      </div>
                    </q-card>
                    <div class="q-px-sm" v-else>
                      <q-item-section>
                        <q-item-label>
                          <q-skeleton type="text" />
                        </q-item-label>
                        <br />
                        <q-item-label>
                          <q-skeleton type="text" />
                        </q-item-label>
                        <br />
                        <q-item-label>
                          <q-skeleton type="text" />
                        </q-item-label>
                      </q-item-section>
                    </div>
                  </div>
                  <div class="col-12 flex justify-end q-mt-md">
                    <q-btn
                      @click="onSubmit"
                      unelevated
                      color="primary"
                      type="submit"
                      label="Create"
                    ></q-btn>
                  </div>
                  <!-- <button @click="onSubmit">Create</button> -->
                </div>
              </div>
            </div>
          </div>
        </q-card>
      </div>
    </div>
  </div>
</template>
<script>
import router from "../../router";
import store from "../../store";
import axiosApiInstance from "../../utilites";
export default {
  data: () => ({
    name: null,
    loading: false,
    display_name: null,
    checkall: "",
    form: {
      permission_ids: [],
      name: "",
      display_name: "",
    },
    permissionOpt: [],
  }),
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      axiosApiInstance
        .get("roles/create")
        .then((res) => {
          this.permissionOpt = res.data.permissionOpt;
        })
        .catch((err) => {
          console.log(err);
        })
        .finally(() => {
          setTimeout(() => {
            this.loading = true;
          });
        });
    },
    onSubmit() {
      axiosApiInstance
        .post("/roles", this.form)
        .then((res) => {
          // console.log(res.data.meassage);
          store.dispatch("pushNotification", {
            message: res.data.message,
            type: "success",
          });
          router.push({ name: "role.index" });
        })
        .catch((err) => {
          // console.log(err.response.data.errors.name);
          this.name = err.response.data.errors.name[0];
          this.display_name = "The display name field is required";
        });
    },
  },
};
</script>

<style scoped>
.fieldset-r {
  border-radius: 12px;
  border: 2px solid #bae6fd;
}
</style>
