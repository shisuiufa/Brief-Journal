export default defineNuxtRouteMiddleware((to) => {
  const { bearerToken } = useBearerToken();

  if (bearerToken.value && to.path !== "/") {
    return navigateTo("/");
  }
});
