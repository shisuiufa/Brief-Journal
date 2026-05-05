<script setup lang="ts">
import PostsFilters from '~/components/posts/PostsFilters.vue'
import PostsTable from '~/components/posts/table/PostsTable.vue'

const postStore = usePostStore();

const { data: postsResponse, pending } = await useLazyAsyncData(
    'posts',
    () => postStore.fetchPosts(),
)

const posts = computed(() => {
  return postsResponse.value?.data ?? []
})
</script>

<template>
  <UPage>
    <UPageHeader
        title="Posts"
        description="Create, edit and publish blog posts."
    >
      <template #links>
        <UButton
            to="/posts/create"
            icon="i-lucide-circle-plus"
        >
          Create post
        </UButton>
      </template>
    </UPageHeader>

    <UPageBody>
      <div class="space-y-4">
        <PostsFilters />
        <PostsTable
            :posts="posts"
            :loading="pending"
        />
      </div>
    </UPageBody>
  </UPage>
</template>