import { z } from 'zod';
import { resourceSchema } from './resource';
import { Roles, roleSchema } from "~/resources/role";

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

const userFormBaseSchema = z.object({
    name: z.string().min(1, 'Name is required.'),
    email: z.email('Please enter a valid email address.'),
    role: roleSchema,
})

export const createUserSchema = userFormBaseSchema.extend({
    password: z.string().min(8, 'Password must be at least 8 characters.'),
    password_confirmation: z.string().min(8, 'Please confirm password.'),
}).refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match.',
    path: ['password_confirmation'],
})

export const updateUserSchema = userFormBaseSchema
    .extend({
        password: z
            .string()
            .optional()
            .or(z.literal('')),

        password_confirmation: z
            .string()
            .optional()
            .or(z.literal('')),
    })
    .refine((data) => {
        if (!data.password) return true

        return data.password.length >= 8
    }, {
        message: 'Password must be at least 8 characters.',
        path: ['password'],
    })
    .refine((data) => {
        if (!data.password) return true

        return data.password === data.password_confirmation
    }, {
        message: 'Passwords do not match.',
        path: ['password_confirmation'],
    })

export const tokenSchema = z.object({
    access_token: z.string(),
    token_type: z.string(),
    expires_in: z.number(),
})

export type TokenResource = z.infer<typeof tokenSchema>;
export type CreateUserCredentials = z.infer<typeof createUserSchema>;
export type LoginCredentials = z.infer<typeof loginSchema>;
export type UpdateUserCredentials = z.infer<typeof updateUserSchema>;
