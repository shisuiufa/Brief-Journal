import { defineStore } from "pinia";
import { useApi } from "~/composables/useApi";
import type {
  ResourceCollection,
  ResourceCollectionMeta,
  ResourceItem,
  ResourcePagination,
} from "~/types/api";
import {
  type CreatePostCredentials,
  type PostResource,
  PostStatusFilter,
  type UpdatePostCredentials,
} from "~/resources/post";
import { watchDebounced } from "@vueuse/core";

export const usePostStore = defineStore("post", () => {
  const list = ref<PostResource[]>();
  const meta = ref<ResourceCollectionMeta>();
  const links = ref<ResourcePagination>();

  const search = ref<string>("");
  const status = ref<PostStatusFilter>(PostStatusFilter.All);

  const loading = ref<boolean>(false);

  const fetchPosts = async () => {
    const response = await useApi<ResourceCollection<PostResource>>(
      "/api/admin/posts",
      {
        method: "GET",
        query: {
          search: search.value || undefined,
          status:
            status.value === PostStatusFilter.All ? undefined : status.value,
        },
      },
    );

    list.value = response.data;
    meta.value = response.meta;
    links.value = response.links;

    return response;
  };

  const create = async (credentials: CreatePostCredentials) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi("/api/admin/posts", {
        method: "POST",
        body: objectToFormData(credentials),
      });
    } finally {
      loading.value = false;
    }
  };

  const fetchPost = async (id: string | number) => {
    return await useApi<ResourceItem<PostResource>>(`/api/admin/posts/${id}`, {
      method: "GET",
    });
  };

  const update = async (
    id: string | number,
    credentials: UpdatePostCredentials,
  ) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi(`/api/admin/posts/${id}`, {
        method: "PUT",
        body: objectToFormData({
          ...credentials,
        }),
      });
    } finally {
      loading.value = false;
    }
  };

  const destroy = async (id: string | number) => {
    try {
      if (loading.value) return;

      loading.value = true;

      return await useApi(`/api/admin/posts/${id}`, {
        method: "DELETE",
      });
    } finally {
      loading.value = false;
    }
  };

  watch(status, async () => {
    await fetchPosts();
  });

  watchDebounced(
    search,
    async () => {
      await fetchPosts();
    },
    {
      debounce: 400,
      maxWait: 1000,
    },
  );

  return {
    fetchPosts,
    fetchPost,
    create,
    update,
    destroy,
    meta,
    list,
    links,
    search,
    status,
    loading,
  };
});
