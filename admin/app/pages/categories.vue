<script setup lang="ts">
import TaxonomySection from "~/components/taxonomy/TaxonomySection.vue";
import { Roles } from "~/resources/role";
import {
  taxonomyType,
  type TaxonomyCredentials,
  type TaxonomyResource,
} from "~/resources/taxonomy";
import type { FetchError } from "ofetch";
import {
  useTaxonomyRealtime,
} from "~/composables/useTaxonomyRealtime";

definePageMeta({
  middleware: "role",
  roles: [Roles.Admin, Roles.SuperAdmin],
});

const toast = useToast();
const categoryStore = useCategoryStore();

const { list: categories, loading, meta } = storeToRefs(categoryStore);

const selectedCategory = ref<TaxonomyResource | null>(null);

const { pending } = await useLazyAsyncData("admin-categories", () =>
  categoryStore.fetchCategories(),
);

const getErrorMessage = (error: unknown, fallback: string) => {
  const fetchError = error as FetchError<{ message?: string }>;

  return fetchError.data?.message ?? fallback;
};

const handleSubmit = async (credentials: TaxonomyCredentials) => {
  try {
    if (selectedCategory.value) {
      await categoryStore.update(selectedCategory.value.id, credentials);
    } else {
      await categoryStore.create(credentials);
      categoryStore.setPage(1);
    }

    toast.add({
      title: selectedCategory.value ? "Category updated" : "Category created",
      description: selectedCategory.value
        ? "The category has been updated successfully."
        : "The category has been created successfully.",
      color: "success",
    });

    selectedCategory.value = null;
  } catch (error) {
    toast.add({
      title: "Something went wrong",
      description: getErrorMessage(error, "Failed to save category."),
      color: "error",
    });
  }
};

const handleDelete = async (id: number) => {
  try {
    await categoryStore.destroy(id);

    if (selectedCategory.value?.id === id) {
      selectedCategory.value = null;
    }

    toast.add({
      title: "Category deleted",
      description: "The category has been removed.",
      color: "success",
    });
  } catch (error) {
    toast.add({
      title: "Failed to delete category",
      description: getErrorMessage(error, "Please try again."),
      color: "error",
    });
  }
};

const handlePage = async (page: number) => {
  categoryStore.setPage(page);
};

useTaxonomyRealtime();
</script>

<template>
  <UPage>
    <UPageHeader
      title="Categories"
      description="Create and manage editorial sections used by posts."
    />

    <UPageBody>
      <TaxonomySection
        :type="taxonomyType.Category"
        title="All categories"
        description="Paginated category list."
        :items="categories ?? []"
        :selected="selectedCategory"
        :meta="meta"
        :loading="loading"
        :pending="pending"
        @submit="handleSubmit"
        @edit="selectedCategory = $event"
        @cancel="selectedCategory = null"
        @delete="handleDelete"
        @page="handlePage"
      />
    </UPageBody>
  </UPage>
</template>
