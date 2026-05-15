<script setup lang="ts">
import TrendingTagsItem from '@/components/trending-tags/TrendingTagsItem.vue';
import type { TagResource } from '@/resources/taxonomy';

defineProps<{
  tags: TagResource[];
}>();
</script>

<template>
  <div v-if="tags.length" class="flex flex-wrap gap-3">
    <TrendingTagsItem
      v-for="tag in tags"
      :key="tag.id"
      :label="tag.name"
      :slug="tag.slug"
      :count="tag.posts_count"
    />
  </div>

  <p v-else class="text-muted text-sm leading-relaxed">No trending tags yet.</p>
</template>
