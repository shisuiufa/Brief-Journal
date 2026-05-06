<script setup lang="ts">
import type { TableColumn } from "@nuxt/ui";
import type { PostResource } from "~/resources/post";
import { getPostStatusColor, getPostStatusLabel } from "~/utils/postStatus";

defineProps<{
  loading?: boolean;
  posts: PostResource[];
}>();

const emit = defineEmits<{
  "delete-post": [id: PostResource["id"]];
}>();

const columns: TableColumn<PostResource>[] = [
  {
    accessorKey: "title",
    header: "Title",
  },
  {
    accessorKey: "author",
    header: "Author",
  },
  {
    accessorKey: "status",
    header: "Status",
  },
  {
    accessorKey: "published_at",
    header: "Published",
  },
  {
    id: "actions",
  },
];
</script>

<template>
  <UCard>
    <UTable :data="posts" :columns="columns" :loading="loading">
      <template #title-cell="{ row }">
        <div>
          <p class="font-medium">
            {{ row.original.title }}
          </p>
        </div>
      </template>

      <template #author-cell="{ row }">
        <div>
          <p class="font-medium first-letter:uppercase">
            {{ row.original.author?.name ?? "unknown" }}
          </p>
        </div>
      </template>

      <template #status-cell="{ row }">
        <UBadge :color="getPostStatusColor(row.original.status)" variant="soft">
          {{ getPostStatusLabel(row.original.status) }}
        </UBadge>
      </template>

      <template #published_at-cell="{ row }">
        <span class="text-muted">
          {{
            row.original.published_at
              ? formatDate(row.original.published_at)
              : "Not published"
          }}
        </span>
      </template>

      <template #actions-cell="{ row }">
        <UDropdownMenu
          :items="[
            [
              {
                label: 'Edit',
                icon: 'i-lucide-pencil',
                to: `/posts/${row.original.id}/edit`,
              },
              {
                label: 'Delete',
                icon: 'i-lucide-trash',
                color: 'error',
                onSelect() {
                  emit('delete-post', row.original.id);
                },
              },
            ],
          ]"
        >
          <UButton
            icon="i-lucide-ellipsis"
            color="neutral"
            variant="ghost"
            square
          />
        </UDropdownMenu>
      </template>
    </UTable>
  </UCard>
</template>
