import { z } from 'zod'
import { resourceSchema } from '@/resources/resource'

export const taxonomyType = {
  Category: 'category',
  Tag: 'tag',
} as const

export type TaxonomyType = (typeof taxonomyType)[keyof typeof taxonomyType]

export const taxonomySchema = resourceSchema.extend({
  name: z.string(),
  slug: z.string(),
  posts_count: z.number().default(0),
})

export const categorySchema = taxonomySchema
export const tagSchema = taxonomySchema

export type TaxonomyResource = z.infer<typeof taxonomySchema>
export type CategoryResource = z.infer<typeof categorySchema>
export type TagResource = z.infer<typeof tagSchema>
