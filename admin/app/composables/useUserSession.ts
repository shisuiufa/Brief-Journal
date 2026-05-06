import type { UserResource } from "~/resources/user";

interface ProfileStorage {
  user: UserResource | null;
}

const profileStorageKey = "profile";

export const useUserSession = () => {
  const user = useState<UserResource | null>("current-user", () => null);

  const readFromStorage = (): UserResource | null => {
    if (!import.meta.client) {
      return null;
    }

    const profileData = localStorage.getItem(profileStorageKey);

    if (!profileData) {
      return null;
    }

    try {
      const profile = JSON.parse(profileData) as ProfileStorage;

      return profile.user ?? null;
    } catch {
      localStorage.removeItem(profileStorageKey);

      return null;
    }
  };

  const writeToStorage = (value: UserResource | null) => {
    localStorage.setItem(
      profileStorageKey,
      JSON.stringify({
        user: value,
      }),
    );
  };

  const setUser = (value: UserResource) => {
    user.value = value;
    writeToStorage(value);
  };

  const clearUser = () => {
    user.value = null;

    localStorage.removeItem(profileStorageKey);
  };

  const hydrateUser = () => {
    user.value = readFromStorage();
  };

  if (!user.value) {
    hydrateUser();
  }

  return {
    user,
    setUser,
    clearUser,
  };
};
