<script setup lang="ts">
import TaxonomySection from "~/components/taxonomy/TaxonomySection.vue";
import { Roles } from "~/resources/role";
import {
  taxonomyType,
  type TaxonomyCredentials,
  type TaxonomyResource,
} from "~/resources/taxonomy";
import type { FetchError } from "ofetch";

definePageMeta({
  middleware: "role",
  roles: [Roles.Admin, Roles.SuperAdmin],
});

const toast = useToast();
const tagStore = useTagStore();

const { list: tags, loading, meta } = storeToRefs(tagStore);

const selectedTag = ref<TaxonomyResource | null>(null);

const { pending, refresh } = await useLazyAsyncData("admin-tags", () =>
  tagStore.fetchTags(),
);

const getErrorMessage = (error: unknown, fallback: string) => {
  const fetchError = error as FetchError<{ message?: string }>;

  return fetchError.data?.message ?? fallback;
};

const handleSubmit = async (credentials: TaxonomyCredentials) => {
  try {
    if (selectedTag.value) {
      await tagStore.update(selectedTag.value.id, credentials);
    } else {
      await tagStore.create(credentials);
      tagStore.setPage(1);
    }

    toast.add({
      title: selectedTag.value ? "Tag updated" : "Tag created",
      description: selectedTag.value
        ? "The tag has been updated successfully."
        : "The tag has been created successfully.",
      color: "success",
    });

    selectedTag.value = null;
    await refresh();
  } catch (error) {
    toast.add({
      title: "Something went wrong",
      description: getErrorMessage(error, "Failed to save tag."),
      color: "error",
    });
  }
};

const handleDelete = async (id: number) => {
  try {
    await tagStore.destroy(id);

    if (selectedTag.value?.id === id) {
      selectedTag.value = null;
    }

    toast.add({
      title: "Tag deleted",
      description: "The tag has been removed.",
      color: "success",
    });

    await refresh();
  } catch (error) {
    toast.add({
      title: "Failed to delete tag",
      description: getErrorMessage(error, "Please try again."),
      color: "error",
    });
  }
};

const handlePage = async (page: number) => {
  tagStore.setPage(page);
  await refresh();
};
</script>

<template>
  <UPage>
    <UPageHeader
      title="Tags"
      description="Create and manage topic labels used by posts."
    />

    <UPageBody>
      <TaxonomySection
        :type="taxonomyType.Tag"
        title="All tags"
        description="Paginated tag list."
        :items="tags ?? []"
        :selected="selectedTag"
        :meta="meta"
        :loading="loading"
        :pending="pending"
        @submit="handleSubmit"
        @edit="selectedTag = $event"
        @cancel="selectedTag = null"
        @delete="handleDelete"
        @page="handlePage"
      />
    </UPageBody>
  </UPage>
</template>
