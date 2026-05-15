<script setup lang="ts">
import CategoryTab from '@/components/category/CategoryTab.vue';
import { useCategoryStore } from '@/stores/useCategoryStore.ts';
import { storeToRefs } from 'pinia';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const categoryStore = useCategoryStore();

const { categories } = storeToRefs(categoryStore);

const handleSelect = (slug: string) => {
  const nextCategory = route.query.category === slug ? undefined : slug;

  router.push({
    name: 'home',
    query: {
      ...route.query,
      category: nextCategory,
      page: undefined,
    },
  });
};
</script>

<template>
  <div class="px-2 flex justify-center flex-wrap gap-2">
    <CategoryTab
      v-for="category in categories"
      :key="category.id"
      :category="category"
      :label="category.name"
      :active="route.query.category === category.slug"
      @select="handleSelect(category.slug)"
    />
  </div>
</template>
