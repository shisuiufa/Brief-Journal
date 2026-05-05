<script setup lang="ts">
import DashboardRecentPostItem from "~/components/dashboard/DashboardRecentPostItem.vue";

const props = defineProps<{
  loading?: boolean
}>()

const postStore = usePostStore()
const { list } = storeToRefs(postStore)

const recentPosts = computed(() => {
  return (list.value ?? []).slice(0, 3)
})
</script>

<template>
  <div v-if="props.loading" class="divide-y divide-default">
    <div
        v-for="index in 3"
        :key="index"
        class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0"
    >
      <div class="min-w-0 flex-1">
        <USkeleton class="h-5 w-64 max-w-full rounded-md" />
        <USkeleton class="mt-2 h-4 w-28 rounded-md" />
      </div>

      <USkeleton class="h-6 w-20 rounded-full" />
    </div>
  </div>

  <div v-else-if="!recentPosts.length" class="py-8 text-center text-sm text-muted">
    No posts found.
  </div>

  <div v-else class="divide-y divide-default">
    <DashboardRecentPostItem
        v-for="post in recentPosts"
        :key="post.id"
        :post="post"
    />
  </div>
</template>
