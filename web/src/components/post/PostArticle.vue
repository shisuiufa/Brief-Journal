<script setup lang="ts">
import PostAuthor from '@/components/post/PostAuthor.vue';
import UiBadge from '@/components/ui/UiBadge.vue';
import UiCard from '@/components/ui/UiCard.vue';
import type { PostResource } from '@/resources/post';
import { formatDate } from '@/utils/formatDate';
import { formatViews } from '@/utils/formatNumber';
import { CalendarDaysIcon, ClockIcon, EyeIcon } from '@heroicons/vue/24/outline';

defineProps<{
  post: PostResource;
  readTime: string;
}>();
</script>

<template>
  <div class="min-w-0">
    <UiCard class="overflow-hidden">
      <div class="relative aspect-video min-h-64 overflow-hidden bg-button-tag">
        <img
          v-if="post.image_url"
          :src="post.image_url"
          :alt="post.title"
          class="h-full w-full object-cover"
        />
        <div
          class="absolute inset-0 bg-linear-to-t from-black/75 via-black/20 to-transparent"
        ></div>
        <div class="absolute bottom-0 left-0 right-0 p-5 xl:p-8">
          <div v-if="post.categories.length" class="mb-4 flex flex-wrap gap-2">
            <UiBadge v-for="category in post.categories" :key="category.id">
              {{ category.name }}
            </UiBadge>
          </div>

          <h1 class="max-w-4xl text-3xl font-black leading-tight text-white md:text-5xl">
            {{ post.title }}
          </h1>
        </div>
      </div>

      <div class="p-5 xl:p-8">
        <div
          class="mb-8 flex flex-col gap-5 border-b border-default pb-6 md:flex-row md:items-center md:justify-between"
        >
          <PostAuthor :post="post" />

          <dl class="grid grid-cols-1 gap-3 text-muted sm:grid-cols-3">
            <div class="flex items-center gap-2 text-xs">
              <CalendarDaysIcon class="size-4 text-accent" />
              <span>{{ formatDate(post.published_at) }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
              <ClockIcon class="size-4 text-accent" />
              <span>{{ readTime }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
              <EyeIcon class="size-4 text-accent" />
              <span>{{ formatViews(post.views_count) }}</span>
            </div>
          </dl>
        </div>

        <p v-if="post.excerpt" class="mb-8 max-w-3xl text-xl leading-9 text-foreground">
          {{ post.excerpt }}
        </p>

        <div
          class="max-w-3xl space-y-6 text-base leading-8 text-muted [&_blockquote]:border-l-4 [&_blockquote]:border-accent [&_blockquote]:bg-accent-soft [&_blockquote]:px-5 [&_blockquote]:py-4 [&_blockquote]:text-foreground [&_h2]:pt-3 [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-foreground [&_h3]:pt-2 [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-foreground [&_a]:font-semibold [&_a]:text-accent [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6"
          v-html="post.content"
        ></div>
      </div>
    </UiCard>
  </div>
</template>
