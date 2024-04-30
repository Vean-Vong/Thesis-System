<script setup>
import avatar1 from "@/assets/images/avatars/avatar-1.png";
import { useAuthStore } from "@/store/module/auth.module";
import constant from "@/constants";
const store = useAuthStore();
const avatarBadgeProps = {
  dot: true,
  location: "bottom right",
  offsetX: 3,
  offsetY: 3,
  color: "success",
  bordered: true,
};
const onLogout = () => {
  store.logout();
};
</script>

<template>
  <VBadge v-bind="avatarBadgeProps">
    <VAvatar style="cursor: pointer" color="primary" variant="tonal">
      <!-- <VImg :src="avatar1" /> -->
      <template v-if="store._user.photo_path">
        <VImg :src="constant.storagePath + store._user.photo_path" />
      </template>
      <template v-else>
        <VImg :src="avatar1" />
      </template>
      <!-- SECTION Menu -->
      <VMenu activator="parent" width="230" location="bottom end" offset="14px">
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge v-bind="avatarBadgeProps">
                  <VAvatar color="primary" size="40" variant="tonal">
                    <template v-if="store._user.photo_path">
                      <VImg :src="constant.storagePath + store._user.photo_path" />
                    </template>
                    <template v-else>
                      <VImg :src="avatar1" />
                    </template>
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ store._user.username }}
            </VListItemTitle>
            <VListItemSubtitle class="text-disabled">
              {{ store._user.email }}
            </VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Profile -->
          <VListItem link to="/account-settings">
            <template #prepend>
              <VIcon class="me-2" icon="mdi-account-outline" size="22" />
            </template>

            <VListItemTitle>ព័ត៌មានផ្ទាល់ខ្លួន</VListItemTitle>
          </VListItem>

          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click="onLogout">
            <template #prepend>
              <VIcon class="me-2" icon="mdi-logout-variant" size="22" />
            </template>

            <VListItemTitle>ចាកចេញ</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
