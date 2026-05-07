import { defineStore } from "pinia";
import { useApi } from "~/composables/useApi";
import type {
  ResourceCollection,
  ResourceCollectionMeta,
  ResourcePagination,
} from "~/types/api";
import type {
  CreateTagCredentials,
  TagResource,
  UpdateTagCredentials,
} from "~/resources/taxonomy";

export const useTagStore = defineStore("tag", () => {
  const list = ref<TagResource[]>();
  const meta = ref<ResourceCollectionMeta>();
  const links = ref<ResourcePagination>();
  const page = ref<number>(1);
  const loading = ref<boolean>(false);

  const fetchTags = async () => {
    const response = await useApi<ResourceCollection<TagResource>>(
      "/api/admin/tags",
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

  const create = async (credentials: CreateTagCredentials) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi("/api/admin/tags", {
        method: "POST",
        body: credentials,
      });
    } finally {
      loading.value = false;
    }
  };

  const update = async (id: string | number, credentials: UpdateTagCredentials) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi(`/api/admin/tags/${id}`, {
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

      return await useApi(`/api/admin/tags/${id}`, {
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
    fetchTags,
    setPage,
    create,
    update,
    destroy,
  };
});
