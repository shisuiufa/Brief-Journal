import {defineStore} from "pinia";
import {useUserSession} from "~/composables/useUserSession";
import type {LoginCredentials, TokenResource, UserResource} from "~/resources/user";
import {useApi} from "~/composables/useApi";
import type {ResourceItem} from "~/types/api";

export const useProfileStore = defineStore("profile", () => {
    const { user, setUser, clearUser } = useUserSession();
    const { setToken, clearToken } = useBearerToken();
    const loading = ref(false);

    const login = async (credentials: LoginCredentials) => {
        try {
            if(loading.value) return;

            loading.value = true;

            const response = await useApi<ResourceItem<{
                user: UserResource,
                token: TokenResource
            }>>('/api/auth/login', {
                method: 'POST',
                body: credentials
            });

            if (response.data) {
                setUser(response.data.user);
                setToken(response.data.token.access_token, response.data.token.expires_in);
            }
        } finally {
            loading.value = false;
        }
    }

    const logout = async () => {
        try {
            if(loading.value) return;

            loading.value = true;

            await useApi<void>('/api/auth/logout', {
                method: "POST"
            });

            clearUser();
            clearToken();
        } finally {
            loading.value = false;
        }
    }

    return {
        user,
        loading,
        login,
        logout,
    }
})
