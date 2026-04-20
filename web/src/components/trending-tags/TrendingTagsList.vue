<script setup lang="ts">
import TrendingTagsItem from '@/components/trending-tags/TrendingTagsItem.vue'

const tags = [
  { id: 1, label: 'Design Systems', count: 18 },
  { id: 2, label: 'Frontend', count: 24 },
  { id: 3, label: 'UX Writing', count: 9 },
  { id: 4, label: 'Performance', count: 12 },
  { id: 5, label: 'Accessibility', count: 15 },
  { id: 6, label: 'Product Strategy', count: 7 },
]
</script>

<template>
  <div class="flex flex-wrap gap-3">
    <TrendingTagsItem v-for="tag in tags" :key="tag.id" :label="tag.label" :count="tag.count" />
  </div>
</template>
