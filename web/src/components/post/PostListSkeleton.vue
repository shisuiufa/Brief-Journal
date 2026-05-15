<script setup lang="ts">
import PostItemSkeleton from '@/components/post/PostItemSkeleton.vue';
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 xl:gap-8">
    <PostItemSkeleton v-for="item in 4" :key="item" />
  </div>
</template>
