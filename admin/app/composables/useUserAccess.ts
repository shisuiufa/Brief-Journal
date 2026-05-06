import type { Role } from "~/resources/role";

export const useUserAccess = () => {
  const { user } = useUserSession();

  const roles = computed(() => {
    return user.value?.roles ?? [];
  });

  const hasRole = (role: Role) => {
    return roles.value.includes(role);
  };

  const hasAnyRole = (requiredRoles: Role[]) => {
    return requiredRoles.some((role) => roles.value.includes(role));
  };

  return {
    roles,
    hasRole,
    hasAnyRole,
  };
};
