import {Roles} from "~/resources/role";

export default defineNuxtRouteMiddleware((to) => {
    const { hasAnyRole } = useUserAccess()

    const requiredRoles = to.meta.roles as Roles[] | undefined

    if (requiredRoles?.length && !hasAnyRole(requiredRoles)) {
        return navigateTo('/403')
    }
});