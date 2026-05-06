<script setup lang="ts">
import type { PostResource } from "~/resources/post";

defineProps<{
  post: PostResource;
}>();

const getStatusColor = (status: string) => {
  return status === "published" ? "success" : "warning";
};
</script>

<template>
  <div
    class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0"
  >
    <div class="min-w-0">
      <p class="font-medium truncate">
        {{ post.title }}
      </p>

      <p class="text-sm text-muted">
        {{ formatDate(post.published_at) }}
      </p>
    </div>

    <UBadge :color="getStatusColor(post.status)" variant="soft">
      {{ post.status }}
    </UBadge>
  </div>
</template>
