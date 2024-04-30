<template>
  <VCard>
    <VCardText>
      <h2>App Setting</h2>
    </VCardText>
    <VCardActions>
      <VRow>
        <VCol cols="5">
          <p class="text-center">app name</p>
        </VCol>
        <VCol cols="7">
          <VTextField id="id" v-model="items.app_name" class="w-75" />
        </VCol>
        <VCol cols="5">
          <p class="text-center">app logo</p>
        </VCol>
        <VCol cols="7">
          <AppImageUpload
            v-model="items.app_logo"
            :placeholder="data[0].app_logo"
            title="app logo"
          />

          <!--
            <VImg
            width="200"
            :src="data[0].app_logo"
            /> 
          -->
        </VCol>
        <VCol cols="5">
          <p class="text-center">app login image</p>
        </VCol>
        <VCol cols="7">
          <AppImageUpload
            v-model="items.app_login_image"
            :placeholder="data[0].app_login_image"
            title="app login image"
          />

          <!-- <VImg :src="data[0].app_login_image" /> -->
        </VCol>
        <VCol cols="12" class="text-center">
          <VBtn class="w-25" color="primary" border @click="onSubmit">
            Submit
          </VBtn>
        </VCol>
      </VRow>
    </VCardActions>
  </VCard>
</template>

<script setup>
import api from "@/plugins/utilites";
import AppImageUpload from "@/components/AppImageUpload.vue";
// import { useAuthStore } from "@/plugins/auth.module"

// const data = useAuthStore()._settings

const items = ref({
  app_name: data[0].app_name,
  app_logo: null,
  app_login_image: null,
});

const onSubmit = () => {
  console.log(items.value);

  // api.post("/settings-create", items.value).then(res => {
  //   console.log(res)
  // })
};
</script>

<route lang="yaml">
meta:
  title: App Setting
  layout: default
  subject: Auth
</route>
