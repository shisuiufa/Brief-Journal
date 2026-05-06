import { defineStore } from "pinia";
import { useUserSession } from "~/composables/useUserSession";
import type {
  LoginCredentials,
  TokenResource,
  UserResource,
} from "~/resources/user";
import { useApi } from "~/composables/useApi";
import type { ResourceItem } from "~/types/api";
import type {
  UpdateProfileCredentials,
  UpdateProfilePasswordCredentials,
} from "~/resources/profile";

export const useProfileStore = defineStore("profile", () => {
  const { setUser, clearUser } = useUserSession();
  const { setToken, clearToken } = useBearerToken();
  const loading = ref(false);

  const login = async (credentials: LoginCredentials) => {
    try {
      if (loading.value) return;

      loading.value = true;

      const response = await useApi<
        ResourceItem<{
          user: UserResource;
          token: TokenResource;
        }>
      >("/api/auth/login", {
        method: "POST",
        body: credentials,
      });

      if (response.data) {
        setUser(response.data.user);
        setToken(
          response.data.token.access_token,
          response.data.token.expires_in,
        );
      }
    } finally {
      loading.value = false;
    }
  };

  const logout = async () => {
    try {
      if (loading.value) return;

      loading.value = true;

      await useApi("/api/auth/logout", {
        method: "POST",
      });

      clearUser();
      clearToken();
    } finally {
      loading.value = false;
    }
  };

  const updateProfile = async (credentials: UpdateProfileCredentials) => {
    try {
      if (loading.value) return;

      loading.value = true;

      const res = await useApi<ResourceItem<UserResource>>(
        "/api/admin/profile",
        {
          method: "PATCH",
          body: credentials,
        },
      );

      if (res.data) {
        setUser(res.data);
      }
    } finally {
      loading.value = false;
    }
  };

  const updatePassword = async (
    credentials: UpdateProfilePasswordCredentials,
  ) => {
    try {
      if (loading.value) return;

      loading.value = true;

      await useApi("/api/admin/profile/password", {
        method: "PATCH",
        body: {
          current_password: credentials.currentPassword,
          password: credentials.password,
          password_confirmation: credentials.passwordConfirmation,
        },
      });
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    login,
    logout,
    fetch,
    updateProfile,
    updatePassword,
  };
});
