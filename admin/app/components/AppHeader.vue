<script setup lang="ts">
const emit = defineEmits<{
  toggle: []
}>()
</script>

<template>
  <div class="h-(--ui-header-height) shrink-0 flex items-center px-4 border-b border-default">
    <UButton
        icon="i-lucide-panel-left"
        color="neutral"
        variant="ghost"
        aria-label="Toggle sidebar"
        class="cursor-pointer"
        @click="emit('toggle')"
    />
  </div>
</template>
