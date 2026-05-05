import { z } from 'zod';
import { resourceSchema } from './resource';
import {roleSchema} from "~/resources/role";

export const userSchema = resourceSchema.extend({
    name: z.string(),
    email: z.email(),
    roles: z.array(roleSchema),
});

export type UserResource = z.infer<typeof userSchema>;

export const loginSchema = z.object({
    email: z.email('Please enter a valid email address.'),
    password: z.string().min(8, 'Password must be at least 8 characters.')
})

export const updateUserSchema = z.object({
    name: z.string().min(1, 'Name is required.'),
    email: z.email('Please enter a valid email address.'),
    password: z
        .string()
        .min(8, 'Password must be at least 8 characters.')
        .optional()
        .or(z.literal(''))
})

export type LoginCredentials = z.infer<typeof loginSchema>;
export type UpdateUserCredentials = z.infer<typeof updateUserSchema>;

export const tokenSchema = z.object({
    access_token: z.string(),
    token_type: z.string(),
    expires_in: z.number(),
})

export type TokenResource = z.infer<typeof tokenSchema>;
