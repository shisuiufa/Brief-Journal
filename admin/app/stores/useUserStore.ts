import {defineStore} from "pinia";
import type {ResourceCollection, ResourceCollectionMeta, ResourceItem, ResourcePagination} from "~/types/api";
import type { UserResource } from "~/resources/user";
import { RoleFilter } from "~/resources/role";
import { useApi } from "~/composables/useApi";
import {watchDebounced} from "@vueuse/core";

export const useUserStore = defineStore("user", () => {
    const list = ref<UserResource[]>()
    const meta = ref<ResourceCollectionMeta>();
    const links = ref<ResourcePagination>();

    const search = ref<string>('');
    const role = ref<RoleFilter>(RoleFilter.All);

    const fetchUsers = async () => {
        const response = await useApi<ResourceCollection<UserResource>>('/api/admin/users', {
            method: 'GET',
            query: {
                search: search.value || undefined,
                role: role.value === RoleFilter.All ? undefined : role.value,
            },
        });

        list.value = response.data;
        meta.value = response.meta;
        links.value = response.links;

        return response
    }

    const fetchUser = async (id: string | number) => {
        return await useApi<ResourceItem<UserResource>>(`/api/admin/users/${id}`, {
            method: 'GET',
        });
    }

    watch(role, async () => {
        await fetchUsers()
    })

    watchDebounced(
        search,
        async () => {
            await fetchUsers()
        },
        {
            debounce: 400,
            maxWait: 1000,
        },
    )

    return {
        list,
        meta,
        links,
        search,
        role,
        fetchUsers,
        fetchUser
    }
})