import { defineStore } from "pinia";
import { useApi } from "~/composables/useApi";
import type {
  ResourceCollection,
  ResourceCollectionMeta,
  ResourcePagination,
} from "~/types/api";
import type {
  CategoryResource,
  CreateCategoryCredentials,
  UpdateCategoryCredentials,
} from "~/resources/taxonomy";

export const useCategoryStore = defineStore("category", () => {
  const list = ref<CategoryResource[]>();
  const meta = ref<ResourceCollectionMeta>();
  const links = ref<ResourcePagination>();
  const page = ref<number>(1);
  const loading = ref<boolean>(false);

  const fetchCategories = async () => {
    const response = await useApi<ResourceCollection<CategoryResource>>(
      "/api/admin/categories",
      {
        method: "GET",
        query: {
          page: page.value,
        },
      },
    );

    list.value = response.data;
    meta.value = response.meta;
    links.value = response.links;

    return response;
  };

  const create = async (credentials: CreateCategoryCredentials) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi("/api/admin/categories", {
        method: "POST",
        body: credentials,
      });
    } finally {
      loading.value = false;
    }
  };

  const update = async (
    id: string | number,
    credentials: UpdateCategoryCredentials,
  ) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi(`/api/admin/categories/${id}`, {
        method: "PUT",
        body: credentials,
      });
    } finally {
      loading.value = false;
    }
  };

  const destroy = async (id: string | number) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi(`/api/admin/categories/${id}`, {
        method: "DELETE",
      });
    } finally {
      loading.value = false;
    }
  };

  const setPage = (value: number) => {
    page.value = value;
  };

  return {
    list,
    meta,
    links,
    page,
    loading,
    fetchCategories,
    setPage,
    create,
    update,
    destroy,
  };
});
