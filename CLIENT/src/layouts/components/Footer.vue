<template>
  <div class="h-100 d-flex flex-column justify-space-between">
    <!-- 👉 Footer: left content -->
    <span class="d-flex align-center">
      &copy;
      {{ new Date().getFullYear() }}
      {{ $t('Powered By') }}
      {{ $t('student_credit') }}
    </span>
  </div>
</template>
