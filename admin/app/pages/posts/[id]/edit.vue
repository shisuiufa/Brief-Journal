<script setup lang="ts">
import PostForm from '~/components/posts/form/PostForm.vue'

const route = useRoute()
const postStore = usePostStore()

const postId = computed(() => String(route.params.id))

const { data: postResponse, error } = await useAsyncData(
    () => `post-${postId.value}`,
    () => postStore.fetchPost(postId.value),
)

const post = computed(() => postResponse.value?.data ?? null)

if (error.value || !post.value) {
  await navigateTo('/posts')
}
</script>

<template>
  <UPage>
    <UPageHeader
        title="Edit post"
        description="Update post content, cover image and publish settings."
    >
      <template #links>
        <UButton
            to="/posts"
            icon="i-lucide-arrow-left"
            color="neutral"
            variant="ghost"
        >
          Back to posts
        </UButton>
      </template>
    </UPageHeader>

    <UPageBody>
      <PostForm
          v-if="post"
          :post="post"
          mode="edit"
      />
    </UPageBody>
  </UPage>
</template>
