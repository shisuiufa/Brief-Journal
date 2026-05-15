import { useApi } from '@/composables/useApi';
import type { TagResource } from '@/resources/taxonomy';
import type { ResourceCollection } from '@/types/api';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useTagStore = defineStore('tag', () => {
  const api = useApi();

  const trendingTags = ref<TagResource[]>([]);

  const fetchTrendingTags = async () => {
    const res = await api<ResourceCollection<TagResource>>('/api/tags');

    trendingTags.value = res.data;
  };

  return {
    trendingTags,
    fetchTrendingTags,
  };
});
