<script setup lang="ts">
import UiBadge from '@/components/ui/UiBadge.vue'
import UiCard from '@/components/ui/UiCard.vue'
import type { PostResource } from '@/resources/post'
import { RouterLink } from 'vue-router'

defineProps<{
  post: PostResource
}>()
</script>

<template>
  <UiCard v-if="post.tags.length" class="p-5">
    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-muted">Topics</p>

    <div class="flex flex-wrap gap-2">
      <RouterLink
        v-for="tag in post.tags"
        :key="tag.id"
        :to="{ name: 'home', query: { tag: tag.slug } }"
      >
        <UiBadge>{{ tag.name }}</UiBadge>
      </RouterLink>
    </div>
  </UiCard>
</template>
