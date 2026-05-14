import { z } from "zod";
import { resourceSchema } from "~/resources/resource";
import { taxonomySchema } from "~/resources/taxonomy";
import { userSchema } from "~/resources/user";

export const PostStatus = {
  Draft: "draft",
  Published: "published",
} as const;

export const PostStatusFilter = {
  All: "all",
  Draft: PostStatus.Draft,
  Published: PostStatus.Published,
} as const;

export type PostStatusFilter =
  (typeof PostStatusFilter)[keyof typeof PostStatusFilter];

export const postStatusSchema = z.enum([
  PostStatus.Draft,
  PostStatus.Published,
]);

export const postSchema = resourceSchema.extend({
  title: z.string(),
  slug: z.string(),
  excerpt: z.string().nullable(),
  content: z.string(),
  image_url: z.string().nullable(),
  status: postStatusSchema,
  published_at: z.string().nullable(),
  author: userSchema,
  categories: z.array(taxonomySchema).default([]),
  tags: z.array(taxonomySchema).default([]),
  views_count: z.number().default(0),
  featured_at: z.string().nullable(),
  is_featured: z.boolean().default(false),
});

const stripHtml = (value: string) => {
  return value
    .replace(/<[^>]*>/g, "")
    .replace(/&nbsp;/g, " ")
    .trim();
};

const postFormSchema = z.object({
  title: z.string().min(1, "Title is required"),
  slug: z.string().min(1, "Slug is required"),
  excerpt: z.string().optional(),
  content: z.string().refine((value) => stripHtml(value).length > 0, {
    message: "Content is required",
  }),
  status: postStatusSchema,
  category_ids: z.array(z.number()),
  tag_ids: z.array(z.number()),
  is_featured: z.boolean().default(false),
});

export const createPostSchema = postFormSchema
  .extend({
    image: z.instanceof(File, { message: "Image is required" }).nullable(),
  })
  .refine((data) => data.image instanceof File, {
    path: ["image"],
    message: "Image is required",
  });

export const updatePostSchema = postFormSchema.extend({
  image: z.instanceof(File).nullable(),
});

export type PostResource = z.infer<typeof postSchema>;
export type CreatePostCredentials = z.infer<typeof createPostSchema>;
export type UpdatePostCredentials = z.infer<typeof updatePostSchema>;
