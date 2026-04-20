<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'
import AppMain from '@/components/AppMain.vue'
</script>
<template>
  <div class="bg-background flex min-h-dvh w-full flex-col">
    <AppHeader />
    <AppMain class="grow">
      <slot />
    </AppMain>
    <AppFooter />
  </div>
</template>
