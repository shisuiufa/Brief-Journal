<script setup lang="ts">
import PostItem from '@/components/post/PostItem.vue'
import type { PostResource } from '@/resources/post'

defineProps<{ posts: PostResource[] }>()
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 xl:gap-8">
    <PostItem :post="post" v-for="post in posts" :key="post.id" />
  </div>
</template>
