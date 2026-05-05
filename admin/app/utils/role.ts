import { Roles } from '~/resources/role'

export type RoleBadgeColor =
    | 'error'
    | 'primary'
    | 'info'
    | 'neutral'
    | 'success'
    | 'secondary'
    | 'warning'

export const getRoleColor = (role: Roles): RoleBadgeColor => {
    switch (role) {
        case Roles.SuperAdmin:
            return 'error'

        case Roles.Admin:
            return 'primary'

        case Roles.Editor:
            return 'info'

        case Roles.User:
            return 'neutral'
    }
}