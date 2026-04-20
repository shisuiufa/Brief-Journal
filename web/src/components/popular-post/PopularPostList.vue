<script setup lang="ts">
import PopularPostItem from '@/components/popular-post/PopularPostItem.vue'

const posts = [
  {
    id: 1,
    rank: 1,
    title: 'Principles of Minimalist UI',
    views: '45k views',
    readTime: '4 min read',
  },
  {
    id: 2,
    rank: 2,
    title: 'Why Good Product Writing Feels Invisible',
    views: '31k views',
    readTime: '6 min read',
  },
  {
    id: 3,
    rank: 3,
    title: 'Designing Interfaces That Explain Themselves',
    views: '22k views',
    readTime: '5 min read',
  },
  {
    id: 4,
    rank: 4,
    title: 'Performance Wins Users Actually Notice',
    views: '18k views',
    readTime: '7 min read',
  },
]
</script>

<template>
  <div class="flex flex-col gap-6">
    <PopularPostItem
      v-for="post in posts"
      :key="post.id"
      :rank="post.rank"
      :title="post.title"
      :views="post.views"
      :read-time="post.readTime"
    />
  </div>
</template>
