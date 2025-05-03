<script setup>
import navItems from '@/navigation/vertical'
import { useThemeConfig } from '@core/composable/useThemeConfig'

// Components
import Footer from '@/layouts/components/Footer.vue'
import NavBarI18n from '@/layouts/components/NavBarI18n.vue'
import NavBarNotifications from '@/layouts/components/NavBarNotifications.vue'
import NavbarShortcuts from '@/layouts/components/NavbarShortcuts.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import AppLoading from '@/layouts/components/AppLoading.vue'

// import NavSearchBar from '@/layouts/components/NavSearchBar.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import { useAuthStore } from '@/plugins/auth.module'

// @layouts plugin
import { VerticalNavLayout } from '@layouts'
import router from '@/router'

const setting = useAuthStore().setting

const { appRouteTransition, isLessThanOverlayNavBreakpoint } = useThemeConfig()
const { width: windowWidth } = useWindowSize()
</script>

<template>
  <VerticalNavLayout :nav-items="navItems">
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <IconBtn
          v-if="isLessThanOverlayNavBreakpoint(windowWidth)"
          id="vertical-nav-toggle-btn"
          class="ms-n3"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon
            size="26"
            icon="tabler-menu-2"
          />
        </IconBtn>

        <!-- <NavSearchBar class="ms-lg-n3" /> -->
        <p
          style="margin: 0 auto"
          :class="[$i18n.locale == 'en' ? 'font-weight-bold' : '', 'text-h5 textNavHeader text-primary fontKMEF2']"
        >
          {{ $i18n.locale == 'en' ? setting?.name_en : setting?.name_kh }}
        </p>

        <VSpacer />

        <NavBarI18n class="me-1" />
        <NavbarThemeSwitcher class="me-1" />
        <!-- <NavbarShortcuts class="me-1" /> -->
        <!-- <NavBarNotifications class="me-2" /> -->
        <UserProfile />
      </div>
    </template>

    <!-- 👉 Pages -->
    <RouterView v-slot="{ Component }">
      <Transition
        :name="appRouteTransition"
        mode="out-in"
      >
        <div
          :class="[$i18n.locale == 'en' ? '' : 'fontKBTB']"
          :style="[$i18n.locale == 'en' ? '' : 'font-size: 13.5px']"
        >
          <Component :is="Component" />
        </div>
      </Transition>
    </RouterView>

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>

    <!-- 👉 Customizer -->
    <!-- <TheCustomizer /> -->

    <!-- 👉 Loading -->
    <!-- <AppLoading /> -->
  </VerticalNavLayout>
</template>

<style scoped>
@media screen and (max-width: 600px) {
  .textNavHeader {
    display: none;
  }
}
</style>
