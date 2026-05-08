<script setup lang="ts">
import PostAuthor from '@/components/post/PostAuthor.vue'
import UiCard from '@/components/ui/UiCard.vue'
import type { PostResource } from '@/resources/post'
import { getReadingTime } from '@/utils/readingTime'
import { ClockIcon } from '@heroicons/vue/24/outline'
import { computed } from 'vue'

const props = defineProps<{ post: PostResource }>()

const visibleCategories = computed(() => props.post.categories.slice(0, 2))
const hiddenCategoriesCount = computed(() => Math.max(props.post.categories.length - 2, 0))
</script>

<template>
  <UiCard
    as="RouterLink"
    :to="{ name: 'post', params: { slug: post.slug } }"
    class="block p-5 xl:p-8 cursor-pointer"
  >
    <div class="w-full xl:h-80 rounded-2xl overflow-hidden mb-3">
      <img
        v-if="post?.image_url"
        :src="post.image_url"
        :alt="post.title"
        class="w-full h-full object-cover"
      />
    </div>

    <div v-if="post.categories.length" class="mb-3 flex min-h-7 flex-wrap items-center gap-2">
      <span
        v-for="category in visibleCategories"
        :key="category.id"
        class="bg-button-tag border-default text-muted inline-flex h-7 max-w-full items-center rounded-full border px-3 text-xs font-semibold"
      >
        {{ category.name }}
      </span>

      <span
        v-if="hiddenCategoriesCount"
        class="border-default text-muted inline-flex h-7 items-center rounded-full border bg-transparent px-2.5 text-xs font-semibold"
      >
        +{{ hiddenCategoriesCount }}
      </span>
    </div>

    <h1 class="font-bold text-xl mb-1 text-foreground leading-normal">
      {{ post.title }}
    </h1>

    <p class="text-sm mb-3 text-muted leading-normal">
      {{ post.excerpt }}
    </p>

    <div class="flex items-center justify-between gap-4">
      <PostAuthor :post="post" />

      <span class="text-muted inline-flex shrink-0 items-center gap-1.5 text-xs font-medium">
        <ClockIcon class="size-4 text-accent" />
        {{ getReadingTime(post.content) }}
      </span>
    </div>
  </UiCard>
</template>
