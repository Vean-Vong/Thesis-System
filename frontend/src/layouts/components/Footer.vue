<script setup>
import { useMainStore } from "@/store/main";
const store = useMainStore();
</script>
<template>
  <div class="w-100 h-100 d-flex align-center justify-space-between">
    <!-- 👉 Footer: left content -->
    <span class="d-flex align-center">
      &copy;
      {{ new Date().getFullYear() }}
      <!-- រចនាគេហទំព័រដោយ
      @
      <a
        href="http://facebook.com/houseofcoder.dev"
        target="_blank"
        rel="noopener noreferrer"
        class="text-primary ms-1"
      >ផ្ទះអ្នកអាយធី</a> -->
    </span>
    <!-- 👉 Footer: right content -->
    <!-- <span class="d-md-flex gap-x-4 text-primary d-none">
      <a href="http://facebook.com/houseofcoder.dev" target="noopener noreferrer"
        >License</a
      >
      <a href="http://facebook.com/houseofcoder.dev" target="noopener noreferrer"
        >Documentation</a
      >
    </span> -->
  </div>
</template>
