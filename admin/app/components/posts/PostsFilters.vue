<script setup lang="ts">
import { PostStatusFilter } from "~/resources/post";

const postStore = usePostStore();

const { search, status } = storeToRefs(postStore);

const statusItems = [
  {
    label: "All",
    value: PostStatusFilter.All,
  },
  {
    label: "Published",
    value: PostStatusFilter.Published,
  },
  {
    label: "Draft",
    value: PostStatusFilter.Draft,
  },
];
</script>

<template>
  <UCard>
    <div
      class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
      <UInput
        v-model="search"
        icon="i-lucide-search"
        placeholder="Search posts..."
        class="sm:max-w-xs"
      />

      <USelect v-model="status" :items="statusItems" class="sm:w-48" />
    </div>
  </UCard>
</template>
