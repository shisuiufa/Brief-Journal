<script setup lang="ts">
import PostsFilters from '~/components/posts/PostsFilters.vue'
import PostsTable from '~/components/posts/table/PostsTable.vue'

const postStore = usePostStore();

const { list } = storeToRefs(postStore)

const { pending } = await useLazyAsyncData(
    'posts',
    () => postStore.fetchPosts(),
)
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
            :posts="list ?? []"
            :loading="pending"
        />
      </div>
    </UPageBody>
  </UPage>
</template>