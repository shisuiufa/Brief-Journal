<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import { TagIcon } from '@heroicons/vue/24/outline'
import TrendingTagsList from '@/components/trending-tags/TrendingTagsList.vue'
import UiBadge from '@/components/ui/UiBadge.vue'
</script>

<template>
  <UiCard class="overflow-hidden px-6 py-5" as="section">
    <div class="mb-5 flex items-start justify-between gap-4">
      <div>
        <h2 class="text-foreground flex items-center gap-2 text-lg font-bold">
          <TagIcon class="size-5" />
          Trending Tags
        </h2>
        <p class="text-muted mt-2 text-sm leading-relaxed">
          Explore the themes readers keep opening this week.
        </p>
      </div>

      <UiBadge>Hot</UiBadge>
    </div>

    <TrendingTagsList />
  </UiCard>
</template>
