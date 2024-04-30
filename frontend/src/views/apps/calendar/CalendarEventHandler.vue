<script setup>
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import { VForm } from "vuetify/components/VForm";
import { useCalendarStore } from "./useCalendarStore";
import avatar1 from "@images/avatars/avatar-1.png";
import avatar2 from "@images/avatars/avatar-2.png";
import avatar3 from "@images/avatars/avatar-3.png";
import avatar5 from "@images/avatars/avatar-5.png";
import avatar6 from "@images/avatars/avatar-6.png";
import avatar7 from "@images/avatars/avatar-7.png";
import { requiredValidator, urlValidator } from "@validators";
import api from "@/plugins/utilites";
import { useAuthStore } from "@/plugins/auth.module";

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  event: {
    type: null,
    required: true,
  },
});

const emit = defineEmits([
  "update:isDrawerOpen",
  "addEvent",
  "updateEvent",
  "removeEvent",
]);

const authStore = useAuthStore();

const store = useCalendarStore();
const refForm = ref();

// 👉 Event
const event = ref(JSON.parse(JSON.stringify(props.event)));

const resetEvent = () => {
  event.value = JSON.parse(JSON.stringify(props.event));
  nextTick(() => {
    refForm.value?.resetValidation();
  });
};

watch(() => props.isDrawerOpen, resetEvent);

const removeEvent = () => {
  emit("removeEvent", event.value.id);

  // Close drawer
  emit("update:isDrawerOpen", false);
};

const handleSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      // If id exist on id => Update event
      if ("id" in event.value) emit("updateEvent", event.value);
      // Else => add new event
      else emit("addEvent", event.value);

      // Close drawer
      emit("update:isDrawerOpen", false);
    }
  });
};

const staffOptions = ref([]);

onMounted(() => {
  api
    .post("/employees-all")
    .then((res) => {
      staffOptions.value = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });
});

// 👉 Form
const onCancel = () => {
  // Close drawer
  emit("update:isDrawerOpen", false);
  nextTick(() => {
    refForm.value?.reset();
    resetEvent();
    refForm.value?.resetValidation();
  });
};

const startDateTimePickerConfig = computed(() => {
  const config = {
    enableTime: !event.value.allDay,
    dateFormat: `Y-m-d${event.value.allDay ? "" : " H:i"}`,
  };

  if (event.value.end) config.maxDate = event.value.end;

  return config;
});

const endDateTimePickerConfig = computed(() => {
  const config = {
    enableTime: !event.value.allDay,
    dateFormat: `Y-m-d${event.value.allDay ? "" : " H:i"}`,
  };

  if (event.value.start) config.minDate = event.value.start;

  return config;
});

const dialogModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
};
</script>

<template>
  <VNavigationDrawer
    temporary
    location="end"
    :model-value="props.isDrawerOpen"
    width="600"
    class="scrollable-content"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- 👉 Header -->
    <AppDrawerHeaderSection
      :title="event.id ? 'Update Event' : 'Add Event'"
      @cancel="$emit('update:isDrawerOpen', false)"
    >
      <template #beforeClose>
        <IconBtn v-show="event.id" @click="removeEvent">
          <VIcon size="18" icon="tabler-trash" />
        </IconBtn>
      </template>
    </AppDrawerHeaderSection>

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- SECTION Form -->
          <VForm ref="refForm" @submit.prevent="handleSubmit">
            <VRow>
              <!-- 👉 Title -->
              <VCol cols="12">
                <AppTextField
                  v-model="event.title"
                  label="Title"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 👉 Calendar -->
              <VCol cols="12">
                <AppSelect
                  v-model="event.meeting_type_id"
                  label="Meeting Type"
                  :rules="[requiredValidator]"
                  :items="store.availableCalendars"
                  :item-title="(item) => item.name"
                  :item-value="(item) => item.id"
                >
                  <template #selection="{ item }">
                    <div
                      v-show="event.meeting_type_id"
                      class="align-center"
                      :class="event.meeting_type_id ? 'd-flex' : ''"
                    >
                      <VBadge
                        :color="item.raw.color"
                        inline
                        dot
                        class="pa-1 pb-2"
                      />
                      <span>{{ item.raw.name }}</span>
                    </div>
                  </template>
                </AppSelect>
              </VCol>

              <!-- 👉 Start date -->
              <VCol cols="12">
                <AppDateTimePicker
                  :key="JSON.stringify(startDateTimePickerConfig)"
                  v-model="event.start"
                  :rules="[requiredValidator]"
                  label="Start date"
                  :config="startDateTimePickerConfig"
                />
              </VCol>

              <!-- 👉 End date -->
              <VCol cols="12">
                <AppDateTimePicker
                  :key="JSON.stringify(endDateTimePickerConfig)"
                  v-model="event.end"
                  label="End date"
                  :config="endDateTimePickerConfig"
                />
              </VCol>
              <!-- :rules="[requiredValidator]" -->

              <!-- 👉 All day -->
              <VCol cols="12">
                <VSwitch v-model="event.allDay" label="All day" />
              </VCol>

              <!-- 👉 Event URL -->
              <VCol cols="12">
                <AppTextField
                  v-model="event.url"
                  label="Event URL"
                  :rules="[urlValidator]"
                  type="url"
                />
              </VCol>

              <!-- 👉 Guests -->

              <VCol
                cols="12"
                v-if="
                  authStore._user.permissions.includes('add staff to events')
                "
              >
                <AppSelect
                  v-model="event.staffs"
                  label="Staff"
                  :items="staffOptions"
                  :item-title="(item) => item.khmer_name"
                  :item-value="(item) => item.id"
                  chips
                  multiple
                  eager
                />
              </VCol>

              <!-- 👉 Location -->
              <VCol cols="12">
                <AppTextField v-model="event.location" label="Location" />
              </VCol>

              <!-- 👉 Description -->
              <VCol cols="12">
                <AppTextarea v-model="event.description" label="Description" />
              </VCol>

              <!-- 👉 Form buttons -->
              <VCol cols="12">
                <VBtn type="submit" class="me-3"> Submit </VBtn>
                <VBtn variant="tonal" color="secondary" @click="onCancel">
                  Cancel
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
          <!-- !SECTION -->
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>

<style scoped>
.cell-background {
  background-color: #22993c;
}
</style>
