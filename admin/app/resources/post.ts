import { z } from 'zod';
import {resourceSchema} from "~/resources/resource";
import {userSchema} from "~/resources/user";

export const PostStatus = {
    Draft: 'draft',
    Published: 'published',
} as const

export const postStatusSchema = z.enum([
    PostStatus.Draft,
    PostStatus.Published,
])

export const postSchema = resourceSchema.extend({
    title: z.string(),
    slug: z.string(),
    excerpt: z.string(),
    content: z.string(),
    image_url: z.string(),
    status: z.string(),
    published_at: z.string(),
    author: userSchema,
})

export const createPostSchema = z.object({
    title: z.string().min(1, 'Title is required'),
    slug: z.string().min(1, 'Slug is required'),
    image: z.instanceof(File, { message: 'Image is required' }).nullable(),
    excerpt: z.string().optional(),
    content: z.string().min(1, 'Content is required'),
    status: postStatusSchema,
}).refine((data) => data.image instanceof File, {
    path: ['image'],
    message: 'Image is required',
})

export type PostResource = z.infer<typeof postSchema>
export type CreatePostCredentials = z.infer<typeof createPostSchema>