<!-- eslint-disable import/no-unresolved -->
<script setup>
import AccountSetting from './settings/AccountSetting.vue'
import SecuritySetting from './settings/SecuritySetting.vue'
import AppSetting from './settings/AppSetting.vue'
import { useAuthStore } from '@/plugins/auth.module'

const user = useAuthStore().user

const activeTab = ref(0)
</script>

<template>
  <div>
    <VTabs
      v-model="activeTab"
      fixed-tabs
    >
      <VTab> {{ $t('Profile Details') }} </VTab>
      <VTab> {{ $t('Change Password') }} </VTab>
      <VTab v-if="user.can('access app settings')">
        {{ $t('App Setting') }}
      </VTab>
    </VTabs>

    <VWindow
      v-model="activeTab"
      class="mt-6 disable-tab-transition"
      :touch="false"
    >
      <VWindowItem>
        <AccountSetting />
      </VWindowItem>
      <VWindowItem>
        <SecuritySetting />
      </VWindowItem>
      <VWindowItem v-if="user.can('access app settings')">
        <AppSetting />
      </VWindowItem>
    </VWindow>
  </div>
</template>

<route lang="yaml">
meta:
  title: Role
  layout: default
  subject: Auth
  active: 'account-settings'
</route>
