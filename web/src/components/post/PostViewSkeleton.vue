<script setup lang="ts">
import PostArticleSkeleton from '@/components/post/PostArticleSkeleton.vue'
import PostSidebarSkeleton from '@/components/post/PostSidebarSkeleton.vue'
</script>

<template>
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-[minmax(0,1fr)_320px] xl:gap-8">
    <PostArticleSkeleton />
    <PostSidebarSkeleton />
  </div>
</template>
