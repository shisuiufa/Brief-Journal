<script setup lang="ts">
import PostsFilters from "~/components/posts/PostsFilters.vue";
import PostsTable from "~/components/posts/table/PostsTable.vue";
import { usePostRealtime } from "~/composables/usePostRealtime";

const postStore = usePostStore();
const toast = useToast();

const { list } = storeToRefs(postStore);

const { pending } = await useLazyAsyncData("posts", () =>
  postStore.fetchPosts(),
);

const handleDelete = async (id: number) => {
  try {
    await postStore.destroy(id);
    toast.add({
      title: "Post deleted",
      description: "The post could not be deleted. Please try again.",
      color: "success",
    });
  } catch {
    toast.add({
      title: "Failed to delete post",
      description: "Please try again.",
      color: "error",
    });
  }
};

usePostRealtime();
</script>

<template>
  <UPage>
    <UPageHeader
      title="Posts"
      description="Create, edit and publish blog posts."
    >
      <template #links>
        <UButton to="/posts/create" icon="i-lucide-circle-plus">
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
          @delete-post="handleDelete"
        />
      </div>
    </UPageBody>
  </UPage>
</template>
