<script setup lang="ts">
defineProps<{
  rank: number
  title: string
  views: string
  readTime: string
}>()
</script>

<template>
  <RouterLink
    to=""
    class="group flex w-full items-start gap-4 rounded-2xl text-left transition hover:-translate-y-0.5"
  >
    <span
      class="text-primary/25 group-hover:text-accent/65 w-10 shrink-0 text-4xl leading-none font-black transition"
    >
      {{ String(rank).padStart(2, '0') }}
    </span>

    <div class="border-default flex-1 border-b pb-5 group-last:border-b-0 group-last:pb-0">
      <h3
        class="text-foreground group-hover:text-accent text-base font-semibold leading-snug transition"
      >
        {{ title }}
      </h3>
      <p class="text-muted mt-2 text-sm">{{ views }} - {{ readTime }}</p>
    </div>
  </RouterLink>
</template>
