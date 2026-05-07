<script setup lang="ts">
import type { DropdownMenuItem, TableColumn } from "@nuxt/ui";
import type { TaxonomyResource, TaxonomyType } from "~/resources/taxonomy";

const props = withDefaults(
  defineProps<{
    type: TaxonomyType;
    items: TaxonomyResource[];
    loading?: boolean;
  }>(),
  {
    loading: false,
  },
);

const emit = defineEmits<{
  edit: [item: TaxonomyResource];
  delete: [id: TaxonomyResource["id"]];
}>();

const columns: TableColumn<TaxonomyResource>[] = [
  {
    accessorKey: "name",
    header: "Name",
  },
  {
    accessorKey: "slug",
    header: "Slug",
  },
  {
    id: "actions",
  },
];

const emptyText = computed(() => {
  return props.type === "category" ? "No categories yet." : "No tags yet.";
});

const getActions = (item: TaxonomyResource): DropdownMenuItem[][] => [
  [
    {
      label: "Edit",
      icon: "i-lucide-pencil",
      onSelect() {
        emit("edit", item);
      },
    },
    {
      label: "Delete",
      icon: "i-lucide-trash",
      color: "error",
      onSelect() {
        emit("delete", item.id);
      },
    },
  ],
];
</script>

<template>
  <UCard>
    <UTable :data="items" :columns="columns" :loading="loading">
      <template #empty>
        <div class="py-8 text-center text-sm text-muted">
          {{ emptyText }}
        </div>
      </template>

      <template #name-cell="{ row }">
        <div class="flex items-center gap-3">
          <div
            class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 ring-1 ring-primary/20"
          >
            <UIcon
              :name="type === 'category' ? 'i-lucide-folder' : 'i-lucide-tags'"
              class="size-4 text-primary"
            />
          </div>

          <p class="font-medium truncate">
            {{ row.original.name }}
          </p>
        </div>
      </template>

      <template #slug-cell="{ row }">
        <UBadge color="neutral" variant="soft">
          {{ row.original.slug }}
        </UBadge>
      </template>

      <template #actions-cell="{ row }">
        <div class="flex justify-end">
          <UDropdownMenu :items="getActions(row.original)">
            <UButton
              icon="i-lucide-ellipsis"
              color="neutral"
              variant="ghost"
              square
            />
          </UDropdownMenu>
        </div>
      </template>
    </UTable>
  </UCard>
</template>
