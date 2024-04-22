<template>
  <div class="flex justify-center items-center alert-notify">
    <div>
      <div class="bg-color-bg">
        <div>
          <div class="text-h6">
            <div class="flex justify-between">
              <div class="q-mx-sm">
                <q-avatar dense size="md" color="white" text-color="white">
                  <q-icon name="done" color="green"></q-icon>
                </q-avatar>
              </div>
              <div class="q-mx-sm text-white block">
                <div class="text-h6">
                  {{ item.message }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "@vue/runtime-core";
import { useStore } from "vuex";
import { useQuasar } from "quasar";
import { h } from "vue";
const timeOut = ref(null);
const store = useStore();
const $q = useQuasar();

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
});

onMounted(() => {
  timeOut.value = setTimeout(() => {
    store.dispatch("removeNotification", props.item);
  }, 3000);
});

onBeforeMount(() => {
  clearInterval(timeOut.value);
});
</script>

<style scoped>
.bg-color-bg {
  background-color: #10b981;
  padding: 10px 0px;
  padding-left: 5px;
  border-radius: 5px;
}
</style>
>
