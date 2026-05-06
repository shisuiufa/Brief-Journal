import { z } from "zod";

export const Roles = {
  SuperAdmin: "super-admin",
  Admin: "admin",
  Editor: "editor",
  User: "user",
} as const;

export type Role = (typeof Roles)[keyof typeof Roles];

export const RoleFilter = {
  All: "all",
  SuperAdmin: Roles.SuperAdmin,
  Admin: Roles.Admin,
  Editor: Roles.Editor,
  User: Roles.User,
} as const;

export type RoleFilter = (typeof RoleFilter)[keyof typeof RoleFilter];

export const roleSchema = z.enum(Roles);
