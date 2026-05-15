<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue';
import type { PostResource } from '@/resources/post';
import { formatDate } from '@/utils/formatDate';
import { formatViews } from '@/utils/formatNumber';

defineProps<{
  post: PostResource;
  readTime: string;
}>();
</script>

<template>
  <UiCard class="p-5">
    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-muted">Article details</p>

    <div class="space-y-4">
      <div>
        <p class="text-sm font-semibold text-foreground">Published</p>
        <p class="mt-1 text-sm text-muted">{{ formatDate(post.published_at) }}</p>
      </div>

      <div>
        <p class="text-sm font-semibold text-foreground">Reading time</p>
        <p class="mt-1 text-sm text-muted">{{ readTime }}</p>
      </div>

      <div>
        <p class="text-sm font-semibold text-foreground">Views</p>
        <p class="mt-1 text-sm text-muted">{{ formatViews(post.views_count) }}</p>
      </div>
    </div>
  </UiCard>
</template>
