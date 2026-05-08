<script setup lang="ts">
defineProps<{
  class?: string
}>()
</script>

<template>
  <div
    aria-hidden="true"
    :class="$props.class"
    class="animate-pulse rounded-md bg-button-tag"
  ></div>
</template>
