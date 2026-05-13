import { defineStore } from 'pinia'
import { useApi } from '@/composables/useApi.ts'
import { ref } from 'vue'
import type { ResourceCollection } from '@/types/api.ts'
import type { CategoryResource } from '@/resources/taxonomy.ts'

export const useCategoryStore = defineStore('category', () => {
  const api = useApi()

  const categories = ref<CategoryResource[]>()

  const fetchCategories = async () => {
    const res = await api<ResourceCollection<CategoryResource>>('/api/categories')

    categories.value = res.data
  }

  return {
    categories,
    fetchCategories,
  }
})
