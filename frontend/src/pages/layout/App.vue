<template>
  <q-layout view="lHh Lpr fFf">
    <q-header elevated class="bg-indigo-10">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
        <q-toolbar-title>
          <q-avatar>
            <img src="/schoollogo.jpg" />
          </q-avatar>
          <span class="title"> Mother of peace primary school </span>
          <div class="alert-notic">
            <Notify
              v-for="notification in $store.state.notifications"
              :key="notification.id"
              :item="notification"
            >
            </Notify>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <q-drawer
      show-if-above
      v-model="leftDrawerOpen"
      side="left"
      bordered
      class="white"
      :width="225"
    >
      <q-scroll-area style="height: calc(100% - 150px); margin-top: 135px">
        <q-list padding class="q-pr-md">
          <q-item clickable v-ripple class="app-text" :to="{ name: 'home' }">
            <q-item-section avatar>
              <q-icon name=" home" />
            </q-item-section>
            <q-item-section> Dashboard </q-item-section>
          </q-item>

          <q-item
            v-if="user?.can('study_class_access')"
            clickable
            v-ripple
            class="app-text"
            :to="{ name: 'studyclass.index' }"
          >
            <q-item-section avatar>
              <q-icon name="co_present" />
            </q-item-section>
            <q-item-section> Class </q-item-section>
          </q-item>
          <q-item
            clickable
            v-ripple
            class="app-text"
            :to="{ name: 'teacher.index' }"
            v-if="user?.can('teacher_access')"
          >
            <q-item-section avatar>
              <q-icon name="portrait" />
            </q-item-section>
            <q-item-section> Teacher </q-item-section>
          </q-item>
          <q-item
            clickable
            v-ripple
            class="app-text"
            :to="{ name: 'student.index' }"
            v-if="user?.can('student_access')"
          >
            <q-item-section avatar>
              <q-icon name=" diversity_3" />
            </q-item-section>
            <q-item-section> Students </q-item-section>
          </q-item>

          <q-expansion-item
            group="link-dialog"
            expand-separator
            icon="settings"
            label="Setting"
          >
            <q-card-section class="full-width">
              <q-item
                clickable
                class="app-text"
                v-ripple
                :to="{ name: 'subject.index' }"
                v-if="user?.can('subject_access')"
              >
                <q-item-section avatar>
                  <q-icon name="menu_book" />
                </q-item-section>
                <q-item-section> Subjects </q-item-section>
              </q-item>

              <q-item
                clickable
                class="app-text"
                v-ripple
                :to="{ name: 'room.index' }"
                v-if="user?.can('room_access')"
              >
                <q-item-section avatar>
                  <q-icon name="dataset" />
                </q-item-section>
                <q-item-section> Room </q-item-section>
              </q-item>

              <q-item
                clickable
                class="app-text"
                v-ripple
                :to="{ name: 'grade.index' }"
                v-if="user?.can('grade_access')"
              >
                <q-item-section avatar>
                  <q-icon name="stairs" />
                </q-item-section>
                <q-item-section> Grade</q-item-section>
              </q-item>
              <q-item
                clickable
                v-ripple
                class="app-text"
                :to="{ name: 'studyyear.index' }"
                v-if="user?.can('study_year_access')"
              >
                <q-item-section avatar>
                  <q-icon name="schedule_send" />
                </q-item-section>
                <q-item-section> Study years </q-item-section>
              </q-item>
              <q-item
                clickable
                v-ripple
                class="app-text"
                :to="{ name: 'role.index' }"
                v-if="user?.can('role_access')"
              >
                <q-item-section avatar>
                  <q-icon name="gpp_good" />
                </q-item-section>
                <q-item-section> Role </q-item-section>
              </q-item>

              <!-- <q-item
                clickable
                v-ripple
                class="app-text"
                :to="{ name: 'permission.index' }"
              >
                <q-item-section avatar>
                  <q-icon name="lock" />
                </q-item-section>
                <q-item-section> Permission </q-item-section>
              </q-item> -->

              <q-item
                clickable
                v-ripple
                class="app-text"
                :to="{ name: 'user.index' }"
                v-if="user?.can('user_access')"
              >
                <q-item-section avatar>
                  <q-icon name="person" />
                </q-item-section>
                <q-item-section> User </q-item-section>
              </q-item>
            </q-card-section>
          </q-expansion-item>
          <q-item clickable v-ripple class="app-text" @click="logout">
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>
            <q-item-section> Log-out </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top" src="/schoolbg.png" style="height: 128px">
        <div class="absolute-bottom bg-transparent">
          <q-avatar size="56px" class="q-mb-sm">
            <img src="/schoollogo.jpg" />
          </q-avatar>
          <div class="text-weight-bold">Razvan Stoenescu</div>
          <div>@rstoenescu</div>
        </div>
      </q-img>
    </q-drawer>
    <q-page-container>
      <q-page>
        <router-view v-slot="{ Component, route }">
          <transition name="fade" mode="out-in">
            <div class="wrapper" :key="route.name">
              <component :is="Component"></component>
            </div>
          </transition>
        </router-view>
      </q-page>
    </q-page-container>
  </q-layout>
</template>
<script>
import { mapGetters } from "vuex";
import Notify from "../../components/Notify.vue";
import store from "../../store";
export default {
  data: () => ({
    notifications: [],
    leftDrawerOpen: false,
  }),
  computed: {
    ...mapGetters("auth", {
      user: "user",
    }),
  },
  beforeCreate() {
    this.$store.dispatch("auth/getUser").catch(() => {});
  },
  methods: {
    logout() {
      this.$store.dispatch("auth/logout");
    },
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
  },
  components: { Notify },
};
</script>
<style scoped lang="scss">
.alert-notic {
  position: absolute;
  z-index: 5;
  top: 0px;
  bottom: 0px;
  right: 0px;
}

.app-text {
  letter-spacing: 1px;
  border-radius: 20px;
}

/* #1976d2 default primary color of Quasar */

.app-text:hover {
  transition: ease;
  transition-duration: 300ms;
  cursor: pointer;
  background-color: #e5e7eb;
  color: #1976d2;
}

.app-breadcrumb-toolbar {
  min-height: 40px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

.wrapper {
  width: 100%;
  min-height: 100vh;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-to {
  position: absolute;
  right: 0;
}

.slide-enter-from {
  position: absolute;
  right: 100%;
}

.slide-leave-to {
  position: absolute;
  left: 100%;
}

.slide-leave-from {
  position: absolute;
  left: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition-property: opacity;
  transition-duration: 0.25s;
}

.fade-enter-active {
  transition-delay: 0.25s;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}
</style>
