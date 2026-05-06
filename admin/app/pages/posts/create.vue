<script setup lang="ts">
import PostForm from "~/components/posts/form/PostForm.vue";
</script>

<template>
  <UPage>
    <UPageHeader
      title="Create post"
      description="Create a new blog post draft or publish it immediately."
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
      <PostForm />
    </UPageBody>
  </UPage>
</template>
