<script setup>
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'

const { t } = useI18n()

const form = ref({})
const refForm = ref()

const permissions = ref([])

onMounted(() => {
  const query = router.currentRoute.value.query

  api
    .post('/roles-edit', { id: query.id })
    .then(res => {
      form.value = res.data.data
    })
    .catch(err => {
      console.log(err)
    })

  api
    .post('/permissions-list')
    .then(res => {
      permissions.value = res.data.data
    })
    .catch(err => {
      console.log(err)
    })
})

const onUpdate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    api
      .post('/roles-update', form.value)
      .then(res => {
        if (res.status == 200) router.back()
      })
      .catch(err => {
        console.log(err)
      })
  }
}

const selectAll = ret => {
  for (const permission of ret.childrens) {
    if (!form.value.permissions.includes(permission.id)) {
      form.value.permissions.push(permission.id)
    }
  }
}

const deselectAll = ret => {
  for (const permission of ret.childrens) {
    const index = form.value.permissions.indexOf(permission.id)
    if (index > -1) {
      form.value.permissions.splice(index, 1)
    }
  }
}

const toSentenceFunction = p => {
  return p.charAt(0).toUpperCase() + p.substr(1)
}
</script>

<template>
  <AppFormCreateTemplate
    cols="10"
    @submit="onUpdate"
    :title="$t('edit roles')"
  >
    <VForm
      class="px-3"
      ref="refForm"
      lazy-validation
    >
      <VRow>
        <VCol cols="12">
          <VRow no-gutters>
            <VCol
              cols="12"
              md="6"
            >
              <AppTextField
                required="true"
                :rules="[v => !!v || $t('Name') + $t('required')]"
                id="name"
                v-model="form.name"
                :label="$t('Name')"
              />
            </VCol>
          </VRow>
        </VCol>

        <VCol cols="12">
          <VCard>
            <VCardText>
              <p class="text-lg">{{ $t('Select Permissions') }}</p>
            </VCardText>
            <VCardActions>
              <VExpansionPanels multiple>
                <VExpansionPanel
                  v-for="(ret, i) in permissions"
                  :key="i"
                >
                  <VExpansionPanelTitle>
                    {{ ret.name }}
                  </VExpansionPanelTitle>
                  <VExpansionPanelText>
                    <div
                      class="flex-1"
                      style="width: 25%"
                    >
                      <VBtn
                        variant="flat"
                        size="x-small"
                        color="primary"
                        @click="selectAll(ret)"
                      >
                        Select All
                      </VBtn>
                      <VBtn
                        variant="flat"
                        size="x-small"
                        color="error"
                        @click="deselectAll(ret)"
                      >
                        Deselect All
                      </VBtn>
                    </div>
                    <VSheet class="d-flex align-content-center align-center flex-wrap">
                      <VCheckbox
                        v-for="(permission, p) in ret.childrens"
                        :key="p"
                        v-model="form.permissions"
                        :label="permission.display_name"
                        :value="permission.id"
                        density="compact"
                        class="pa-0 ma-0 text-caption flex-1"
                        hide-details
                        single-line
                        style="width: 25%"
                      />
                    </VSheet>
                  </VExpansionPanelText>
                </VExpansionPanel>
              </VExpansionPanels>
            </VCardActions>
          </VCard>
        </VCol>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<route lang="yaml">
meta:
  title: Role Update
  layout: default
  subject: Auth
  active: 'role'
</route>

<style scoped>
.list-width {
  width: 25%;
}
@media screen and (max-width: 768px) and (min-width: 320px) {
  .list-width {
    width: 100%;
  }
}
</style>
