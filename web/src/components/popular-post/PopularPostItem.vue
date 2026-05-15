<script setup lang="ts">
import { ClockIcon, EyeIcon } from '@heroicons/vue/24/outline';

defineProps<{
  rank: number;
  slug: string;
  title: string;
  views: string;
  readTime: string;
}>();
</script>

<template>
  <RouterLink
    :to="{ name: 'post', params: { slug } }"
    class="group flex w-full items-start gap-4 rounded-2xl text-left transition hover:-translate-y-0.5"
  >
    <span
      class="text-primary/25 group-hover:text-accent/65 w-10 shrink-0 pt-0.5 text-4xl leading-none font-black transition"
    >
      {{ String(rank).padStart(2, '0') }}
    </span>

    <div class="border-default flex-1 border-b pb-5 group-last:border-b-0 group-last:pb-0">
      <h3
        class="text-foreground group-hover:text-accent text-base font-semibold leading-snug transition"
      >
        {{ title }}
      </h3>

      <div class="text-muted mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs">
        <span class="inline-flex items-center gap-1.5">
          <EyeIcon class="size-4 text-accent" />
          {{ views }}
        </span>

        <span class="inline-flex items-center gap-1.5">
          <ClockIcon class="size-4 text-accent" />
          {{ readTime }}
        </span>
      </div>
    </div>
  </RouterLink>
</template>
