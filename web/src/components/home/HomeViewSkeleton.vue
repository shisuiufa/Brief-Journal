<script setup lang="ts">
import FeaturedPostSkeleton from '@/components/featured-post/FeaturedPostSkeleton.vue';
import HomeFiltersSkeleton from '@/components/home/HomeFiltersSkeleton.vue';
import HomeSidebarSkeleton from '@/components/home/HomeSidebarSkeleton.vue';
import PostListSkeleton from '@/components/post/PostListSkeleton.vue';
import UiSkeleton from '@/components/ui/UiSkeleton.vue';
</script>

<template>
  <div
    class="grid grid-cols-1 gap-4 xl:gap-8 md:grid-cols-[minmax(0,1fr)_300px] lg:grid-cols-[minmax(0,1fr)_400px]"
  >
    <div class="flex flex-col items-center">
      <FeaturedPostSkeleton class="w-full mb-4 xl:mb-8" />
      <HomeFiltersSkeleton />
      <PostListSkeleton class="w-full mb-4 xl:mb-8" />

      <div class="flex items-center gap-2">
        <UiSkeleton class="size-10 rounded-xl" />
        <UiSkeleton class="size-10 rounded-xl" />
        <UiSkeleton class="size-10 rounded-xl" />
      </div>
    </div>

    <HomeSidebarSkeleton />
  </div>
</template>
