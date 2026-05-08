<script setup lang="ts">
import PostDetailsCard from '@/components/post/PostDetailsCard.vue'
import PostTopicsCard from '@/components/post/PostTopicsCard.vue'
import type { PostResource } from '@/resources/post'

defineProps<{
  post: PostResource
  readTime: string
}>()
</script>

<template>
  <aside class="space-y-4 xl:sticky xl:top-28 xl:self-start">
    <PostDetailsCard :post="post" :read-time="readTime" />
    <PostTopicsCard :post="post" />
  </aside>
</template>
