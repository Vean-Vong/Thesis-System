<script setup>
import DrawerContent from "./DrawerContent.vue";
import { VerticalNavLayout } from "@layouts";

// Components
import Footer from "@/layouts/components/Footer.vue";
import NavbarThemeSwitcher from "@/layouts/components/NavbarThemeSwitcher.vue";
import UserProfile from "@/layouts/components/UserProfile.vue";
import { useAuthStore } from "@/store/module/auth.module";
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar>
      <h1
        class="font-weight-semibold leading-normal text-xl text-uppercase muol-light text-success"
      >
        ប្រព័ន្ធគ្រប់គ្រងសាលារៀន
      </h1>
      <VSpacer />
      <NavbarThemeSwitcher />
      <!-- <VBtn icon variant="text" color="default" class="me-2" size="small">
        <VIcon icon="mdi-bell-outline" size="24" />
      </VBtn> -->
      <UserProfile />
    </template>

    <!-- 👉 Drawer content -->
    <template #navigation-drawer-content>
      <DrawerContent />
    </template>

    <!-- 👉 Pages -->
    <div class="layout-page-content">
      <router-view v-slot="{ Component, route }">
        <transition name="slide-x-transition" mode="out-in">
          <div :key="route.name">
            <component :is="Component"></component>
          </div>
        </transition>
      </router-view>
    </div>

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>

<style lang="scss">
.app-bar-search {
  .v-input__control {
    width: 236px;
  }

  .v-field__outline__start {
    border-radius: 28px 0 0 28px !important;
    flex-basis: 20px !important;
  }

  .v-field__outline__end {
    border-radius: 0 28px 28px 0 !important;
  }
}
.line {
  background: linear-gradient(270deg, rgb(var(--v-theme-success)) 0%, white 300%);
  color: rgb(var(--v-theme-on-success));
  box-shadow: 0px 3px 3px -2px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
    0px 3px 4px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
    0px 1px 8px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12));
}

.v-text-field input {
  line-height: normal !important;
}

.v-card-title {
  line-height: normal !important;
  font-weight: bold !important;
}
</style>
