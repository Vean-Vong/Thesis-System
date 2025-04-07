<!-- eslint-disable import/extensions -->
<!-- eslint-disable import/no-unresolved -->
<script setup>
import { useAuthStore } from '@/plugins/auth.module'
import api from '@/plugins/utilites'
import { useSkins } from '@core/composable/useSkins'
import { useThemeConfig } from '@core/composable/useThemeConfig'

// @layouts plugin
import { AppContentLayoutNav } from '@layouts/enums'
import { onMounted } from 'vue'
import router from '@/router'

import DefaultLayoutWithHorizontalNav from '@/layouts/components/DefaultLayoutWithHorizontalNav.vue'

import DefaultLayoutWithVerticalNav from '@/layouts/components/DefaultLayoutWithVerticalNav.vue'

const { width: windowWidth } = useWindowSize()

const { appContentLayoutNav, switchToVerticalNavOnLtOverlayNavBreakpoint } = useThemeConfig()

// Remove below composable usage if you are not using horizontal nav layout in your app
switchToVerticalNavOnLtOverlayNavBreakpoint(windowWidth)

const { layoutAttrs, injectSkinClasses } = useSkins()

injectSkinClasses()

const authStore = useAuthStore()

onMounted(() => {
  api.post('verify-auth').then(res => {
    if (res.status == 200) {
      authStore.setAuth(res.data.user)
      authStore.setRefreshing(false)
    }
  })
  api.post('app-settings-verify').then(res => {
    if (res.status == 200) {
      authStore.setSettings(res.data.data)
    }
  })
})
</script>

<template>
  <template v-if="appContentLayoutNav === AppContentLayoutNav.Vertical">
    <DefaultLayoutWithVerticalNav v-bind="layoutAttrs" />
  </template>
  <template v-else>
    <DefaultLayoutWithHorizontalNav v-bind="layoutAttrs" />
  </template>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use '@layouts/styles/default-layout';
</style>
