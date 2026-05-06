import type { BadgeProps } from "@nuxt/ui";

export type PostStatus = "draft" | "published";

export const getPostStatusLabel = (status: PostStatus) => {
  return status === "published" ? "Published" : "Draft";
};

export const getPostStatusColor = (status: PostStatus): BadgeProps["color"] => {
  return status === "published" ? "success" : "warning";
};
