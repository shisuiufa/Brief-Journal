import type { UserResource } from "~/resources/user";
import { Roles } from "~/resources/role";

export const useUserPolicy = () => {
  const { user: userSession } = useUserSession();
  const { hasRole } = useUserAccess();

  const isCurrentUser = (user: UserResource) => {
    return userSession.value?.id === user.id;
  };

  const isSuperAdminUser = (user: UserResource) => {
    return user.roles.includes(Roles.SuperAdmin);
  };

  const isAdminUser = (user: UserResource) => {
    return user.roles.includes(Roles.Admin);
  };

  const isRegularUser = (user: UserResource) => {
    return user.roles.includes(Roles.User);
  };

  const canEditUser = (user: UserResource) => {
    if (isCurrentUser(user)) {
      return true;
    }

    if (isRegularUser(user)) {
      return false;
    }

    if (hasRole(Roles.SuperAdmin)) {
      return !isSuperAdminUser(user);
    }

    if (hasRole(Roles.Admin)) {
      return !isSuperAdminUser(user) && !isAdminUser(user);
    }

    return false;
  };

  const canDeleteUser = (user: UserResource) => {
    if (isCurrentUser(user)) {
      return false;
    }

    if (isRegularUser(user)) {
      return false;
    }

    if (hasRole(Roles.SuperAdmin)) {
      return !isSuperAdminUser(user);
    }

    if (hasRole(Roles.Admin)) {
      return !isSuperAdminUser(user) && !isAdminUser(user);
    }

    return false;
  };

  return {
    isCurrentUser,
    isSuperAdminUser,
    isAdminUser,
    isRegularUser,
    canEditUser,
    canDeleteUser,
  };
};
