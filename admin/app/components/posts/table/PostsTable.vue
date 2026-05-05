<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import type {PostResource} from "~/resources/post";

defineProps<{
  loading?: boolean
  posts: PostResource[]
}>()
const columns: TableColumn<Post>[] = [
  {
    accessorKey: 'title',
    header: 'Title',
  },
  {
    accessorKey: 'author',
    header: 'Author',
  },
  {
    accessorKey: 'status',
    header: 'Status',
  },
  {
    accessorKey: 'publishedAt',
    header: 'Published',
  },
  {
    id: 'actions',
  },
]
</script>

<template>
  <UCard>
    <UTable
        :data="posts"
        :columns="columns"
        :loading="loading"
    >
      <template #title-cell="{ row }">
        <div>
          <p class="font-medium">
            {{ row.original.title }}
          </p>

          <p class="text-sm text-muted">
            #{{ row.original.id }}
          </p>
        </div>
      </template>

      <template #status-cell="{ row }">
        <UBadge
            :color="row.original.status === 'Published' ? 'success' : 'warning'"
            variant="soft"
        >
          {{ row.original.status }}
        </UBadge>
      </template>

      <template #publishedAt-cell="{ row }">
        <span class="text-muted">
          {{ row.original.publishedAt ?? 'Not published' }}
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