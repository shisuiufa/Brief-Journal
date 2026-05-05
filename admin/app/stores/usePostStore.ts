import {defineStore} from "pinia";
import {useApi} from "~/composables/useApi";
import type {ResourceCollection, ResourceCollectionMeta, ResourcePagination} from "~/types/api";
import type {CreatePostCredentials, PostResource} from "~/resources/post";

export const usePostStore = defineStore("post", () => {
    const list = ref<PostResource[]>()
    const meta = ref<ResourceCollectionMeta>();
    const links = ref<ResourcePagination>();

    const fetchPosts = async () => {
        const response = await useApi<ResourceCollection<PostResource>>('/api/admin/posts', {
            method: 'GET',
        });

        list.value = response.data;
        meta.value = response.meta;
        links.value = response.links;

        return response
    }

    const create = async (credentials: CreatePostCredentials) => {
       return await useApi('/api/admin/posts', {
            method: 'POST',
            body: objectToFormData(credentials),
        });
    }

    return {
        fetchPosts,
        create,
        meta,
        list,
        links
    }
})
