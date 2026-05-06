export default defineNuxtRouteMiddleware((to) => {
  const { bearerToken, clearToken } = useBearerToken();
  const { clearUser } = useUserSession();

  if (to.path === "/login") return;

  if (!bearerToken.value) {
    clearUser();
    clearToken();

    return navigateTo("/login");
  }
});
