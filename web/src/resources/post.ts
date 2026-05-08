import { z } from 'zod'
import { resourceSchema } from '@/resources/resource'
import { taxonomySchema } from '@/resources/taxonomy'
import { userSchema } from '@/resources/user'

export const PostStatus = {
  Draft: 'draft',
  Published: 'published',
} as const

export const postStatusSchema = z.enum([PostStatus.Draft, PostStatus.Published])

export const postSchema = resourceSchema.extend({
  title: z.string(),
  slug: z.string(),
  excerpt: z.string().nullable(),
  content: z.string(),
  image_url: z.string().nullable(),
  status: postStatusSchema,
  published_at: z.string().nullable(),
  author: userSchema.nullable(),
  categories: z.array(taxonomySchema).default([]),
  tags: z.array(taxonomySchema).default([]),
  views_count: z.number().default(0),
})

export type PostResource = z.infer<typeof postSchema>
