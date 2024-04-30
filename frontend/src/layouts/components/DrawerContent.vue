<script setup>
import upgradeBannerDark from "@/assets/images/pro/upgrade-banner-dark.png";
import upgradeBannerLight from "@/assets/images/pro/upgrade-banner-light.png";
import { VerticalNavLink, VerticalNavSectionTitle } from "@layouts";
import { useTheme } from "vuetify";
import User from "../../class/User";
import { useAuthStore } from "@/store/module/auth.module";
import constant from "@/constants";
const vuetifyTheme = useTheme();
const upgradeBanner = computed(() => {
  return vuetifyTheme.global.name.value === "light"
    ? upgradeBannerLight
    : upgradeBannerDark;
});
import { onMounted, ref } from "vue";
const school = ref("");
const logo = ref("");
onMounted(() => {
  school.value = store._user.school?.khmer_name;
  logo.value = store._user.school?.logo;
});
const store = useAuthStore();

const user = computed(() => {
  const data = {
    user: store?._user,
  };
  return new User(data);
});
</script>

<template>
  <!-- 👉 Nav header -->
  <div>
    <v-sheet class="bg-background pa-4 text-center">
      <v-avatar size="128" color="blue-grey-lighten-5">
        <v-img v-if="logo" :src="constant.storagePath + logo" alt="logo"></v-img>
        <v-img v-else src="../../../public/logo.jpg" alt="logo"></v-img>
      </v-avatar>
    </v-sheet>
    <div class="p-1 pb-4">
      <Transition name="vertical-nav-app-title">
        <h1
          class="font-weight-semibold leading-normal text-uppercase muol-light text-center text-success"
          style="font-size: 14px"
        >
          {{ school }}
        </h1>
      </Transition>
      <!-- </RouterLink> -->
    </div>

    <!-- 👉 Nav items -->
    <ul>
      <VerticalNavLink
        :item="{
          title: 'ផ្ទាំងព័ត៌មាន',
          to: 'index',
          icon: { icon: 'mdi-monitor-dashboard' },
        }"
      />
      <VerticalNavSectionTitle :item="{ heading: 'ព័ត៌មានទូទៅ' }" />
      <VerticalNavLink
        :item="{
          title: 'គ្រូបង្រៀន',
          to: { path: '/teacher' },
          icon: { icon: 'mdi-folder-key-network' },
        }"
        v-if="user.can('teacher_list')"
      />
      <VerticalNavLink
        :item="{
          title: 'សិស្ស',
          to: { path: '/student' },
          icon: { icon: 'mdi-folder-key-network' },
        }"
        v-if="user.can('student_list')"
      />
      <VerticalNavSectionTitle :item="{ heading: 'ការគ្រប់គ្រងថ្នាក់រៀន' }" />
      <VerticalNavLink
        :item="{
          title: 'ថ្នាក់រៀន',
          to: { path: '/academic-class' },
          icon: { icon: 'mdi-folder-key-network' },
        }"
        v-if="user.can('academic_class_list')"
      />
      <VerticalNavLink
        :item="{
          title: 'ឆ្នាំសិក្សា',
          to: { path: '/academic-year' },
          icon: { icon: 'mdi-folder-key-network' },
        }"
        v-if="user.can('academic_year_list')"
      />
      <VerticalNavSectionTitle :item="{ heading: 'ការកំណត់' }" />
      <VerticalNavLink
        :item="{
          title: 'គណនី',
          to: 'account-settings',
          icon: { icon: 'mdi-account-cog-outline' },
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'តួនាទី និងសិទ្ធ',
          to: { path: '/role' },
          icon: { icon: 'mdi-folder-key-network' },
        }"
        v-if="user.can('role_access')"
      />
      <VerticalNavLink
        :item="{
          title: 'អ្នកប្រើប្រាស់',
          to: { path: '/user' },
          icon: { icon: 'mdi-folder-key-network' },
        }"
        v-if="user.can('user_access')"
      />
      <VerticalNavLink
        :item="{
          title: 'សាលារៀន',
          to: { path: '/school' },
          icon: { icon: 'mdi-folder-key-network' },
        }"
        v-if="user.can('school_access')"
      />
    </ul>
  </div>
</template>

<style lang="scss">
// .v-navigation-drawer {
//   height: 100%;
//   .v-navigation-drawer__content {
//     display: flex;
//     flex-direction: column;
//     > ul {
//       flex-grow: 1;
//     }
//   }
// }
.v-list-item__prepend > .v-icon {
  margin-inline-end: 0.5rem !important;
}

.v-list-item__append > .v-icon {
  margin: 0px !important;
}

.v-list-item-title {
  line-height: 2 !important;
}

.muol-light {
  font-family: muol-light;
}

.bg-primary-gradient {
  background: linear-gradient(
    270deg,
    rgb(var(--v-theme-primary)) 0%,
    white 300%
  ) !important;
  color: rgb(var(--v-theme-on-primary)) !important;
  box-shadow: 0px 3px 3px -2px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
    0px 3px 4px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
    0px 1px 8px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12)) !important;
}
.upgrade-banner {
  margin-top: auto;
  // position: absolute;
  // bottom: 13px;
  // left: 50%;
  // transform: translateX(-50%);
}
</style>
