<script setup lang="ts">
import PostSearch from '@/components/post/PostSearch.vue'
import CategoryTabs from '@/components/category/CategoryTabs.vue'
import FeaturedPostCard from '@/components/featured-post/FeaturedPostCard.vue'
import PostList from '@/components/post/PostList.vue'
import HomeSidebar from '@/components/home/HomeSidebar.vue'
import UiPagination from '@/components/ui/paginator/UiPagination.vue'
import UiCard from '@/components/ui/UiCard.vue'
</script>

<template>
  <div class="pt-5 pb-8">
    <div
      class="grid grid-cols-1 gap-4 xl:gap-8 md:grid-cols-[minmax(0,1fr)_300px] lg:grid-cols-[minmax(0,1fr)_400px]"
    >
      <div class="flex flex-col items-center">
        <FeaturedPostCard class="w-full mb-4 xl:mb-8" />

        <UiCard
          class="w-full p-5 mb-4 xl:mb-8 grid grid-cols-1 xl:grid-cols-2 items-start justify-between gap-4 xl:gap-8"
        >
          <PostSearch class="max-w-xl" />
          <CategoryTabs class="max-w-xl" />
        </UiCard>

        <PostList class="w-full mb-4 xl:mb-8" />

        <UiPagination :page="10" :totalPages="51" />
      </div>

      <HomeSidebar />
    </div>
  </div>
</template>
