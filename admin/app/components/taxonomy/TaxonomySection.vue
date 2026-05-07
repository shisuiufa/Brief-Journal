<script setup lang="ts">
import TaxonomyForm from "~/components/taxonomy/TaxonomyForm.vue";
import TaxonomyTable from "~/components/taxonomy/TaxonomyTable.vue";
import type { ResourceCollectionMeta } from "~/types/api";
import type {
  TaxonomyCredentials,
  TaxonomyResource,
  TaxonomyType,
} from "~/resources/taxonomy";

const props = withDefaults(
  defineProps<{
    type: TaxonomyType;
    title: string;
    description: string;
    items: TaxonomyResource[];
    selected?: TaxonomyResource | null;
    meta?: ResourceCollectionMeta | null;
    loading?: boolean;
    pending?: boolean;
  }>(),
  {
    selected: null,
    meta: null,
    loading: false,
    pending: false,
  },
);

const emit = defineEmits<{
  submit: [credentials: TaxonomyCredentials];
  edit: [item: TaxonomyResource];
  cancel: [];
  delete: [id: TaxonomyResource["id"]];
  page: [page: number];
}>();

const page = computed({
  get: () => props.meta?.current_page ?? 1,
  set: (value: number) => emit("page", value),
});
</script>

<template>
  <section class="grid gap-4 xl:grid-cols-[360px_minmax(0,1fr)]">
    <div class="xl:sticky xl:top-6 xl:self-start">
      <TaxonomyForm
        :type="type"
        :item="selected"
        :loading="loading"
        @submit="emit('submit', $event)"
        @cancel="emit('cancel')"
      />
    </div>

    <div class="space-y-3">
      <div>
        <h2 class="text-base font-semibold">
          {{ title }}
        </h2>

        <p class="mt-1 text-sm text-muted">
          {{ description }}
        </p>
      </div>

      <TaxonomyTable
        :type="type"
        :items="items"
        :loading="pending"
        @edit="emit('edit', $event)"
        @delete="emit('delete', $event)"
      />

      <div
        v-if="meta && meta.total > meta.per_page"
        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
      >
        <p class="text-sm text-muted">
          Showing {{ meta.from }}-{{ meta.to }} of {{ meta.total }}
        </p>

        <UPagination
          v-model:page="page"
          :total="meta.total"
          :items-per-page="meta.per_page"
          show-edges
        />
      </div>
    </div>
  </section>
</template>
