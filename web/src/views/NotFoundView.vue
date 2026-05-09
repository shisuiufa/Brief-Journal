<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import NotFoundPanel from '@/components/not-found/NotFoundPanel.vue'

const route = useRoute()

const requestedPath = computed(() => route.fullPath)
</script>

<template>
  <NotFoundPanel :requested-path="requestedPath" />
</template>
