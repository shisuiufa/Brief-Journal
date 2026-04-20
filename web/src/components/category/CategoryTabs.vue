<script setup lang="ts">
import type { Category } from '@/types/category.ts'
import CategoryTab from '@/components/category/CategoryTab.vue'

const model = defineModel<string>({ default: 'all' })

const categories: Category[] = [
  { id: 1, label: 'All', slug: 'all' },
  { id: 2, label: 'Design', slug: 'design' },
  { id: 3, label: 'Development', slug: 'development' },
  { id: 4, label: 'Marketing', slug: 'marketing' },
  { id: 5, label: 'Business', slug: 'business' },
]
</script>

<template>
  <div class="px-2 flex justify-center flex-wrap gap-2">
    <CategoryTab
      v-for="category in categories"
      :key="category.id"
      :category="category"
      :label="category.label"
      :active="model === category.slug"
      @select="model = category.slug"
    />
  </div>
</template>
