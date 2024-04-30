<script setup>
import router from "@/router";
import { onUnmounted, onMounted } from "vue";

const props = defineProps({
  headers: {
    type: Array,
    require: true,
  },
  items: {
    type: Object,
    require: true,
  },
  canView: {
    type: Boolean,
    default: false,
  },
  canEdit: {
    type: Boolean,
    default: false,
  },
  canDelete: {
    type: Boolean,
    default: false,
  },
  canCreate: {
    type: Boolean,
    default: false,
  },
  createUrl: {
    type: String,
    require: true,
  },
  itemsPerPage: {
    type: Number,
    default: 10,
  },
  from: {
    type: Number,
  },
  to: {
    type: Number,
  },
  currentPage: {
    type: Number,
    default: 1,
  },
  itemsLength: {
    type: Number,
  },
  tableTitle: {
    type: String,
    require: false,
  },
  canSearch: {
    default: false,
    type: Boolean,
  },
  columns: {
    type: Array,
    require: true,
  },
  cols: {
    type: [Number, String],
    default: 6,
  },
  btnBack: {
    type: String,
  },
  btnSubmit: {
    type: String,
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["onView", "onEdit", "onDelete", "onUpdate"]);

const itemsPerPage = ref(props.itemsPerPage);

const search = ref("");

const itemsPerPageOption = ref([
  { value: 15, title: "15" },
  { value: 30, title: "30" },
  { value: 50, title: "50" },
  { value: 75, title: "75" },
  { value: 100, title: "100" },
]);

const initDataTable = ref(true);

const paginateTotalVisible = ref(10);

const onScreenChange = (e) => {
  if (e.currentTarget.innerWidth <= 600) {
    paginateTotalVisible.value = 5;
  } else {
    paginateTotalVisible.value = 10;
  }
};

onMounted(() => {
  window.addEventListener("resize", onScreenChange);
});

onUnmounted(() => {
  window.removeEventListener("resize", onScreenChange);
});

const viewCallback = (item) => {
  emit("onView", item);
};

const editCallback = (item) => {
  emit("onEdit", item);
};

const deleteCallback = (item) => {
  emit("onDelete", item);
};

const loadItems = ({ page, itemsPerPage }) => {
  if (!initDataTable.value) {
    emit("onUpdate", {
      page: page,
      limit: itemsPerPage,
    });
  }
  initDataTable.value = false;
};

const handleCreate = () => {
  router.push({ name: props.createUrl });
};
</script>

<template>
  <VRow>
    <VCol cols="12" :md="cols">
      <VCard>
        <v-card-title class="mt-2">
          <p class="text-primary px-6 font-weight-bold fontKSR text-lg-subtitle-1">
            {{ tableTitle }}
          </p>

          <v-divider class="mb-5 px-6"></v-divider>

          <div class="d-flex flex-wrap gap-2 justify-space-between px-6">
            <slot name="forFilter" />
            <div>
              <AppSubmitButton
                v-if="props.canCreate"
                :title="btnSubmit"
                @click.prevent="handleCreate"
              ></AppSubmitButton>
            </div>
          </div>
        </v-card-title>

        <v-card-text>
          <VDataTableServer
            :headers="headers"
            :search="search"
            :loading-text="$t('Loading items...')"
            :items-per-page-text="$t('Items per page')"
            :items="items"
            v-model:items-per-page="itemsPerPage"
            class="pb-3"
            :loading="loading"
            :items-per-page-options="itemsPerPageOption"
            :items-length="props.itemsLength"
            @update:options="loadItems"
          >
            <template #item.no="{ no, index }">
              {{ props.itemsPerPage * (props.currentPage - 1) + index + 1 }}
            </template>
            <template #item.sex="{ item }">
              <p>{{ item.props.title.sex == 1 ? "Male" : "Female" }}</p>
            </template>
            <template #item.color="{ item }">
              <div>
                <v-chip
                  :style="{
                    backgroundColor: item.props.title.color,
                    color: 'white',
                  }"
                  size="small"
                >
                  {{ item.props.title.color }}
                </v-chip>
              </div>
            </template>
            <template #item.actions="{ item }">
              <div class="d-flex gap-1 justify-center">
                <v-menu
                  location="bottom"
                  open-on-hover
                >
                  <template v-slot:activator="{ props }">
                    <IconBtn>
                      <VIcon v-bind="props">mdi-dots-vertical</VIcon>
                    </IconBtn>
                  </template>
                  <VList>
                    <!-- <VListItem>
                      <IconBtn v-if="props.canView" @click="viewCallback(item.id)">
                        <VIcon icon="tabler-eye" color="primary" />
                      </IconBtn>
                    </VListItem> -->
                    <VListItem v-if="props.canView" @click="viewCallback(item.id)">
                      <template v-slot:prepend>
                        <VIcon icon="tabler-eye" color="primary" />
                      </template>
                      <div>{{ $t('Patient Info') }}</div>
                    </VListItem>
                    <VListItem v-if="props.canEdit" @click="editCallback(item.id)">
                      <template v-slot:prepend>
                        <VIcon icon="tabler-edit-circle" color="warning" />
                      </template>
                      <div>{{ $t('Edit') }}</div>
                    </VListItem> 
                    <VListItem v-if="props.canDelete" @click="deleteCallback(item.id)">
                      <template v-slot:prepend>
                        <VIcon icon="mdi-delete-outline" color="error" />
                      </template>
                      <div>{{ $t('Delete') }}</div>
                    </VListItem> 
                  </VList>
                </v-menu>
              </div>
            </template>
            <template #no-data>
              <p>{{ $t("No data avialable") }}</p>
            </template>
          </VDataTableServer>
        </v-card-text>
      </VCard>
    </VCol>
  </VRow>
</template>
