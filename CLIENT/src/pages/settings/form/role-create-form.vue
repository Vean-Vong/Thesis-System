<script setup>
import api from '@/plugins/utilites'
import router from '@/router'

const form = ref({
  name: null,
  permissions: [],
})

const refForm = ref()
const permissions = ref([])

onMounted(() => {
  api
    .post('/permissions-list')
    .then(res => {
      permissions.value = res.data.data
    })
    .catch(err => {
      console.log(err)
    })
})

const onCreate = async () => {
  const { valid } = await refForm.value?.validate()
  if (valid) {
    api
      .post('/roles-store', form.value)
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
    if (!form.value.permissions.includes(permission.name)) {
      form.value.permissions.push(permission.name)
    }
  }
}

const deselectAll = ret => {
  for (const permission of ret.childrens) {
    const index = form.value.permissions.indexOf(permission.name)
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
    @submit="onCreate"
    :title="$t('Create New Role')"
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
                        :value="permission.name"
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
  title: Role Create
  layout: default
  subject: Auth
  active: 'role'
</route>
