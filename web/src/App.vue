<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useRoute } from 'vue-router'
import { computed } from 'vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'

const route = useRoute()

const layoutComponent = computed(() => {
  const layout = route.meta.layout

  switch (layout) {
    default:
      return DefaultLayout
  }
})
</script>

<template>
  <component :is="layoutComponent">
    <RouterView />
  </component>
</template>
