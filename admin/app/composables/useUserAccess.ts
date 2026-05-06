import {Roles} from "~/resources/role";

export const useUserAccess = () => {
    const { user } = useUserSession()

    const roles = computed(() => {
        return user.value?.roles ?? []
    })

    const hasRole = (role: Roles) => {
        return roles.value.includes(role)
    }

    const hasAnyRole = (requiredRoles: Roles[]) => {
        return requiredRoles.some(role => roles.value.includes(role))
    }

    return {
        roles,
        hasRole,
        hasAnyRole
    }
}