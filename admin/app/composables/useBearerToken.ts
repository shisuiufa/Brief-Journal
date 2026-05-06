const REFRESH_BEFORE_EXPIRES_MS = 30_000;

export function useBearerToken() {
  const token = useState<string | null>("bearer_token", () => {
    return localStorage.getItem("bearer_token");
  });

  const expiresAt = useState<number | null>("bearer_token_expires_at", () => {
    const value = localStorage.getItem("bearer_token_expires_at");

    return value ? Number(value) : null;
  });

  const setToken = (value: string, expiresIn?: number) => {
    token.value = value;
    localStorage.setItem("bearer_token", value);

    if (expiresIn) {
      const expiresAtValue = Date.now() + expiresIn * 1000;

      expiresAt.value = expiresAtValue;
      localStorage.setItem("bearer_token_expires_at", String(expiresAtValue));
    }
  };

  const clearToken = () => {
    token.value = null;
    expiresAt.value = null;

    localStorage.removeItem("bearer_token");
    localStorage.removeItem("bearer_token_expires_at");
  };

  const bearerToken = computed(() => {
    return token.value ? `Bearer ${token.value}` : null;
  });

  const needsRefresh = computed(() => {
    if (!expiresAt.value) {
      return false;
    }

    return Date.now() >= expiresAt.value - REFRESH_BEFORE_EXPIRES_MS;
  });

  return {
    token,
    expiresAt,
    setToken,
    clearToken,
    needsRefresh,
    bearerToken,
  };
}
