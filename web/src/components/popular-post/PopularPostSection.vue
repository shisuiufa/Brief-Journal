<script setup lang="ts">
import { ArrowTrendingUpIcon } from '@heroicons/vue/24/outline'
import PopularPostList from '@/components/popular-post/PopularPostList.vue'
import UiCard from '@/components/ui/UiCard.vue'
import UiBadge from '@/components/ui/UiBadge.vue'
</script>

<template>
  <UiCard class="overflow-hidden px-6 py-5" as="section">
    <div class="mb-5 flex items-start justify-between gap-4">
      <div>
        <h2 class="text-foreground flex items-center gap-2 text-lg font-bold">
          <ArrowTrendingUpIcon class="size-5" />
          Popular Posts
        </h2>
        <p class="text-muted mt-2 text-sm leading-relaxed">
          A quick look at the stories getting the most attention right now.
        </p>
      </div>

      <UiBadge>Rising</UiBadge>
    </div>

    <PopularPostList />
  </UiCard>
</template>
