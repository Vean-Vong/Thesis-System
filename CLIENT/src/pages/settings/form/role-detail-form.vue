<script setup>
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'

const { t } = useI18n()
const items = ref([])

onMounted(() => {
  const query = router.currentRoute.value.query

  api
    .post('/roles-show', { id: query.id })
    .then(res => {
      items.value = res.data.data
    })
    .catch(err => {
      console.log(err)
    })
})
</script>

<template>
  <AppFormDetailTemplate
    cols="10"
    md="10"
    :title="$t('Detail Role')"
  >
    <VRow>
      <VCol
        cols="12"
        md="12"
      >
        <VRow>
          <VCol
            cols="12"
            md="6"
          >
            <VList>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ $t('No') }}:</div>
                </template>
                {{ items.id }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ t('Name') }}:</div>
                </template>
                {{ items.name }}
              </VListItem>
              <VListItem>
                <template #prepend>
                  <div class="me-3">{{ t('Permissions Count') }}:</div>
                </template>
                {{ items.permissions_count }}
              </VListItem>
            </VList>
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <VRow
              no-gutters
              class="pa-0 d-none d-lg-flex"
            >
              <VCol
                cols="12"
                md="4"
              >
                <VList lines="one">
                  <VListItem class="py-lg-0 px-lg-3 text-lg-end"> {{ t('Permissions') }}: </VListItem>
                </VList>
              </VCol>
              <VCol
                cols="12"
                md="8"
              >
                <VList lines="one">
                  <VListItem
                    class="pa-lg-0"
                    v-for="item in items.permissions"
                  >
                    {{ t(item) }}
                  </VListItem>
                </VList>
              </VCol>
            </VRow>
            <VExpansionPanels
              variant="accordion"
              class="d-lg-none"
            >
              <VExpansionPanel :title="t('Permissions')">
                <VExpansionPanelText
                  class="pa-lg-0"
                  v-for="item in items.permissions"
                >
                  {{ t(item) }}
                </VExpansionPanelText>
              </VExpansionPanel>
            </VExpansionPanels>
          </VCol>
        </VRow>
      </VCol>
    </VRow>
  </AppFormDetailTemplate>
</template>

<route lang="yaml">
meta:
  title: Role Detail
  layout: default
  subject: Auth
  active: 'role'
</route>
