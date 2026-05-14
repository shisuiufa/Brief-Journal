<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import type { PostResource } from '@/resources/post.ts'
import PostAuthor from '@/components/post/PostAuthor.vue'
import { computed } from 'vue'

const props = defineProps<{
  post: PostResource
}>()

const visibleCategories = computed(() => props.post.categories.slice(0, 2))
const hiddenCategoriesCount = computed(() => Math.max(props.post.categories.length - 2, 0))
</script>

<template>
  <UiCard class="p-5 xl:p-8 cursor-pointer">
    <div class="xl:flex gap-4 md:gap-8">
      <div class="w-full xl:w-2xs mb-3 xl:mt-0 h-auto rounded-2xl overflow-hidden">
        <img
          src="https://i.pinimg.com/originals/4c/0b/e5/4c0be594d9e76702f5a61c08176bb600.jpg"
          alt="post"
          class="w-full h-full object-cover object-center"
        />
      </div>

      <div class="h-auto flex flex-col justify-between">
        <div>
          <div v-if="post.categories.length" class="mb-2 flex min-h-7 flex-wrap items-center gap-2">
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

          <h1 class="font-bold text-3xl mb-3 text-foreground">
            {{ post.title }}
          </h1>

          <p class="text-base mb-3 text-muted">
            {{ post.excerpt }}
          </p>
        </div>

        <PostAuthor :post="post" />
      </div>
    </div>
  </UiCard>
</template>
