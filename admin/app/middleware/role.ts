import type { Role } from "~/resources/role";

export default defineNuxtRouteMiddleware((to) => {
  const { hasAnyRole } = useUserAccess();

  const requiredRoles = to.meta.roles as Role[] | undefined;

  if (requiredRoles?.length && !hasAnyRole(requiredRoles)) {
    return navigateTo("/403");
  }
});
