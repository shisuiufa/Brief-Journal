<script setup lang="ts">
import CategoryTabs from '@/components/category/CategoryTabs.vue'
import FeaturedPostCard from '@/components/featured-post/FeaturedPostCard.vue'
import HomeSidebar from '@/components/home/HomeSidebar.vue'
import HomeViewSkeleton from '@/components/home/HomeViewSkeleton.vue'
import PostList from '@/components/post/PostList.vue'
import PostSearch from '@/components/post/PostSearch.vue'
import UiPagination from '@/components/ui/paginator/UiPagination.vue'
import UiCard from '@/components/ui/UiCard.vue'
import { useCallOnce } from '@/composables/useCallOnce'
import { usePostStore } from '@/stores/usePostStore'
import { useTagStore } from '@/stores/useTagStore'
import { useHead } from '@unhead/vue'
import { storeToRefs } from 'pinia'
import { computed, watch } from 'vue'
import { useCategoryStore } from '@/stores/useCategoryStore.ts'
import { useRoute, useRouter } from 'vue-router'
import { getNumberQuery, getStringQuery } from '@/utils/query.ts'

useHead({
  title: 'Brief Journal',
  meta: [
    {
      name: 'description',
      content:
        'A server-rendered Vue journal with articles on frontend development, design systems, and product engineering.',
    },
    {
      property: 'og:title',
      content: 'Brief Journal',
    },
    {
      property: 'og:description',
      content:
        'A server-rendered Vue journal with articles on frontend development, design systems, and product engineering.',
    },
    {
      property: 'og:type',
      content: 'website',
    },
  ],
})

const postStore = usePostStore()
const tagStore = useTagStore()
const categoryStore = useCategoryStore()
const callOnce = useCallOnce()
const route = useRoute()
const router = useRouter()

const { list: posts, meta, featured } = storeToRefs(postStore)
const isHomeReady = computed(() => meta.value !== null)

const postsQuery = computed(() => ({
  search: getStringQuery(route.query.search),
  category: getStringQuery(route.query.category),
  tag: getStringQuery(route.query.tag),
  page: getNumberQuery(route.query.page),
  per_page: getNumberQuery(route.query.per_page),
}))

const loadData = async () => {
  await Promise.all([
    postStore.fetchPosts(postsQuery.value),
    postStore.fetchPopulars(),
    tagStore.fetchTrendingTags(),
    categoryStore.fetchCategories(),
    postStore.fetchFeatured(),
  ])
}

callOnce(`home:${route.fullPath}`, loadData)

watch(postsQuery, () => {
  void postStore.fetchPosts(postsQuery.value)
})

const handlePageUpdate = (page: number) => {
  void router.push({
    name: 'home',
    query: {
      ...route.query,
      page: page === 1 ? undefined : page,
    },
  })
}
</script>

<template>
  <div class="pt-5 pb-8">
    <div
      v-if="isHomeReady"
      class="grid grid-cols-1 gap-4 xl:gap-8 md:grid-cols-[minmax(0,1fr)_300px] lg:grid-cols-[minmax(0,1fr)_400px]"
    >
      <div class="flex flex-col items-center">
        <FeaturedPostCard v-if="featured" :post="featured" class="w-full mb-4 xl:mb-8" />

        <UiCard
          class="w-full p-5 mb-4 xl:mb-8 grid grid-cols-1 xl:grid-cols-2 items-start justify-between gap-4 xl:gap-8"
        >
          <PostSearch class="max-w-xl" />
          <CategoryTabs class="max-w-xl" />
        </UiCard>

        <PostList class="w-full mb-4 xl:mb-8" :posts="posts ?? []" />

        <UiPagination
          :page="meta?.current_page ?? 1"
          :totalPages="meta?.last_page ?? 1"
          @update:page="handlePageUpdate"
        />
      </div>

      <HomeSidebar />
    </div>

    <HomeViewSkeleton v-else />
  </div>
</template>
