<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue'
import ThemeToggle from '@/components/ThemeToggle.vue'
</script>

<template>
  <header
    class="bg-header-footer border-b border-default sticky top-0 z-20 flex w-full items-center justify-between px-4 md:px-10 py-4 backdrop-blur-xl"
  >
    <AppLogo />
    <ThemeToggle />
    <div class="surface-shadow-down pointer-events-none absolute inset-x-0 top-full h-7"></div>
  </header>
</template>
