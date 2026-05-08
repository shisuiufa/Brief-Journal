<script setup lang="ts">
import type { PostResource } from '@/resources/post'
import { formatDate } from '@/utils/formatDate'

defineProps<{
  post?: PostResource
}>()
</script>

<template>
  <div class="flex items-center gap-3">
    <div v-if="post?.author" class="w-8 h-8 rounded-full overflow-hidden">
      <div
        class="flex h-full w-full items-center justify-center bg-button-tag text-xs font-bold text-muted"
        :aria-label="post.author.name"
      >
        {{ post.author.name.slice(0, 2).toUpperCase() }}
      </div>
    </div>

    <div class="leading-tight">
      <h2 v-if="post?.author" class="text-foreground text-sm font-semibold">
        {{ post.author.name }}
      </h2>

      <p class="text-muted mt-1 text-xs">{{ formatDate(post?.published_at) }}</p>
    </div>
  </div>
</template>
