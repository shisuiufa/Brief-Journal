<script setup lang="ts">
import PopularPostItem from '@/components/popular-post/PopularPostItem.vue';
import type { PostResource } from '@/resources/post';
import { formatViews } from '@/utils/formatNumber';
import { getReadingTime } from '@/utils/readingTime';

defineProps<{
  posts: PostResource[];
}>();
</script>

<template>
  <div v-if="posts.length" class="flex flex-col gap-6">
    <PopularPostItem
      v-for="(post, index) in posts"
      :key="post.id"
      :rank="index + 1"
      :slug="post.slug"
      :title="post.title"
      :views="formatViews(post.views_count)"
      :read-time="getReadingTime(post.content)"
    />
  </div>

  <p v-else class="text-muted text-sm leading-relaxed">No popular posts yet.</p>
</template>
