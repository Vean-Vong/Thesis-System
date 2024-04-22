<template>
  <div>
    <div class="q-mx-sm">
      <div class="q-pa-md">
        <div
          class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize"
        >
          Class
        </div>
        <br />
        <q-card>
          <div v-if="listing" class="q-px-md q-py-lg">
            <!-- Skeleton-besdong -->
            <div>
              <div class="q-pa-sm q-mb-md flex justify-between">
                <div class="Search-input">
                  <q-skeleton></q-skeleton>
                </div>
                <div class="Create-btn">
                  <q-skeleton></q-skeleton>
                </div>
              </div>
              <div class="row">
                <div
                  v-for="item in 8"
                  :key="item.id"
                  class="q-pa-sm col-12 col-lg-3 col-md-3 col-sm-6 col-xs-12"
                >
                  <q-card bordered class="shadow-0">
                    <q-item>
                      <q-item-section>
                        <div class="flex justify-center">
                          <div class="sk-header">
                            <q-skeleton height="50px"></q-skeleton>
                          </div>
                        </div>
                        <br />
                        <q-item-label>
                          <q-skeleton type="text" animation="fade" />
                        </q-item-label>
                        <br />
                        <q-item-label caption>
                          <q-skeleton type="text" animation="fade" />
                        </q-item-label>
                        <br />
                        <q-item-label caption>
                          <q-skeleton type="text" animation="fade" />
                        </q-item-label>
                        <br />
                        <q-item-label caption>
                          <q-skeleton type="text" animation="fade" />
                        </q-item-label>
                        <br />
                        <div class="flex justify-around">
                          <div class="skeleton-bg-card">
                            <q-skeleton></q-skeleton>
                          </div>
                          <div class="skeleton-bg-card">
                            <q-skeleton></q-skeleton>
                          </div>
                          <div class="skeleton-bg-card">
                            <q-skeleton></q-skeleton>
                          </div>
                        </div>
                      </q-item-section>
                    </q-item>
                  </q-card>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="q-px-md q-pb-md">
            <div class="q-py-md flex justify-between">
              <div>
                <q-input
                  outlined
                  bottom-slots
                  label="search"
                  dense
                  debounce="700"
                  v-model="search"
                >
                  <template v-slot:append>
                    <q-icon name="search" />
                  </template>
                </q-input>
                <q-select
                  v-model="study_year_id"
                  :options="study_years"
                  map-options
                  emit-value
                  option-label="name"
                  option-value="id"
                  outlined
                  label="Room"
                  dense
                />
              </div>
              <div>
                <q-btn
                  unelevated
                  color="primary"
                  dense
                  class="q-pr-sm"
                  :to="{ name: 'studyclass.create' }"
                >
                  <q-icon name="add"></q-icon>create
                </q-btn>
              </div>
            </div>
            <div>
              <div>
                <div class="row">
                  <div
                    v-for="item in data"
                    :key="item.id"
                    class="q-pa-xs col-12 col-lg-3 col-md-3 col-sm-6 col-xs-12"
                  >
                    <div class="bg-card">
                      <div
                        class="title-card flex text-h2 text-white justify-center items-center"
                      >
                        {{ item.studyclass_name }}
                      </div>
                      <div class="q-px-md q-my-sm">
                        <table>
                          <tbody class="text-white">
                            <tr>
                              <td>Teacher</td>
                              <td>:</td>
                              <td class="text-lime">{{ item.full_name }}</td>
                            </tr>
                            <tr>
                              <td>Grade</td>
                              <td>:</td>
                              <td class="text-lime">{{ item.grade_name }}</td>
                            </tr>
                            <tr>
                              <td>Study Year</td>
                              <td>:</td>
                              <td class="text-lime">{{ item.studyyear_name }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="flex justify-center q-py-md items-center">
                        <q-btn
                          @click="
                            $router.push({
                              name: 'studyclass.show',
                              params: { studyclass: item.id },
                            })
                          "
                          flat
                          class="q-mx-xs text-capitalize q-px-sm"
                          dense
                        >
                          <q-icon color="amber" name="visibility">
                            <q-tooltip>View</q-tooltip>
                          </q-icon>
                        </q-btn>
                        <q-btn
                          @click="oneEdit(item.id)"
                          flat
                          class="q-mx-xs text-capitalize q-px-sm"
                          dense
                        >
                          <q-icon name="edit" color="green-13">
                            <q-tooltip>Edit</q-tooltip>
                          </q-icon>
                        </q-btn>
                        <q-btn
                          @click="oneDelete(item.id)"
                          flat
                          class="q-mx-xs text-capitalize q-px-sm"
                          dense
                        >
                          <q-icon name="delete_outline" color="red-13">
                            <q-tooltip>Delete</q-tooltip>
                          </q-icon>
                        </q-btn>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </q-card>
        <q-dialog v-model="deleteDialog" persistent>
          <q-card>
            <q-card-section class="row items-center">
              <q-avatar icon="delete" color="red" text-color="white" />
              <span class="q-ml-sm">Are you sure to delete this student?</span>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn flat dense label="No" color="primary" v-close-popup />
              <q-btn unelevated dense label="Yes" @click="confirmOnDelete" color="red" />
            </q-card-actions>
          </q-card>
        </q-dialog>
      </div>
    </div>
  </div>
</template>

<script>
import axiosApiInstance from "../../utilites";
export default {
  data: () => ({
    axiosApiInstance: axiosApiInstance,
    deleteDialog: false,
    listing: true,
    deleting: false,
    getID: null,
    search: "",
    data: [],
    study_year_id: null,
    study_years: [],
  }),
  watch: {
    deleteDialog(newValue) {
      if (newValue == false) {
        this.getID = null;
        this.deleting = null;
      }
    },
    search(newValue, oldValue) {
      if (newValue != oldValue) {
        this.fetchData();
      }
    },
    study_year_id(newValue, oldValue) {
      if (newValue != oldValue) {
        this.fetchData();
      }
    },
  },
  methods: {
    oneEdit(id) {
      this.$router.push(`/studyclass/${id}/edit`);
    },
    oneDelete(id) {
      this.getID = id;
      this.deleteDialog = true;
    },
    confirmOnDelete() {
      this.axiosApiInstance
        .delete(`/studyclasses/${this.getID}`)
        .then((res) => {
          this.$store.dispatch("pushNotification", {
            type: "success",
            message: res.data.message,
          });
          this.deleteDialog = false;
          this.fetchData();
        })
        .catch((err) => {
          this.$store.dispatch("pushNotification", {
            type: "success",
            message: err.data.message,
          });
        });
    },
    fetchData() {
      this.listing = true;
      this.axiosApiInstance
        .get(
          `/studyclasses?search=${this.search}&study_year_id=${this.study_year_id || ""}`
        )
        .then((res) => {
          this.data = res.data.data;
          (this.study_year_id = res.data.study_year_active),
            (this.study_years = res.data.study_years);
        })
        .catch((err) => {
          this.$store.dispatch("pushNotification", {
            type: "success",
            message: err.data.message,
          });
        })
        .finally(() => {
          this.listing = false;
        });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
.bg-card {
  background-color: #2e1065;
  border-radius: 20px;
}

.title-card {
  width: 100%;
  height: 100px;
  display: grid;
  place-content: center;
  border-radius: 10px;
  font-weight: 500;
  text-align: center;
}

.skeleton-bg-card {
  width: 50px;
  opacity: 75%;
}

.sk-header {
  width: 100px;
  opacity: 75%;
}

.Search-input {
  width: 250px;
  opacity: 75%;
}

.Create-btn {
  width: 100px;
  opacity: 75%;
}
</style>
