import { z } from 'zod';

export enum Roles {
    SuperAdmin = 'super-admin',
    Admin = 'admin',
    Editor = 'editor',
    User = 'user',
}

export enum RoleFilter {
    All = 'all',
    SuperAdmin = Roles.SuperAdmin,
    Admin = Roles.Admin,
    Editor = Roles.Editor,
    User = Roles.User,
}

export const roleSchema = z.enum(Roles)
export const roleFilterSchema = z.enum(RoleFilter)