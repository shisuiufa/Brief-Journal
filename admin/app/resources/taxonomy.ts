import { z } from "zod";
import { resourceSchema } from "~/resources/resource";

export const taxonomyType = {
  Category: "category",
  Tag: "tag",
} as const;

export type TaxonomyType = (typeof taxonomyType)[keyof typeof taxonomyType];

export const taxonomySchema = resourceSchema.extend({
  name: z.string(),
  slug: z.string(),
});

export const taxonomyFormSchema = z.object({
  name: z.string().min(1, "Name is required."),
  slug: z.string().min(1, "Slug is required."),
});

export const categorySchema = taxonomySchema;
export const tagSchema = taxonomySchema;

export type TaxonomyResource = z.infer<typeof taxonomySchema>;
export type CategoryResource = z.infer<typeof categorySchema>;
export type TagResource = z.infer<typeof tagSchema>;
export type TaxonomyCredentials = z.infer<typeof taxonomyFormSchema>;
export type CreateCategoryCredentials = TaxonomyCredentials;
export type UpdateCategoryCredentials = TaxonomyCredentials;
export type CreateTagCredentials = TaxonomyCredentials;
export type UpdateTagCredentials = TaxonomyCredentials;
