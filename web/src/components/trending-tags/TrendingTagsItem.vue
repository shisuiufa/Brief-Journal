<script setup lang="ts">
defineProps<{
  label: string
  count: number
}>()
</script>

<template>
  <RouterLink
    to=""
    class="bg-button-tag border border-default shadow-soft group inline-flex cursor-pointer items-center gap-3 rounded-2xl px-4 py-3 text-left transition hover:-translate-y-0.5 hover:bg-accent-soft"
  >
    <span class="text-foreground text-sm font-semibold">{{ label }}</span>
    <span
      class="bg-background text-muted border border-default rounded-full px-2 py-0.5 text-[0.68rem] font-semibold"
    >
      {{ count }}
    </span>
  </RouterLink>
</template>
