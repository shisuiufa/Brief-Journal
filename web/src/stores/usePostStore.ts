import { useApi } from '@/composables/useApi'
import type { PostResource } from '@/resources/post'
import type {
  ResourceCollection,
  ResourceItem,
  ResourceCollectionMeta,
  ResourcePagination,
} from '@/types/api'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePostStore = defineStore('post', () => {
  const api = useApi()

  const list = ref<PostResource[]>([])
  const currentPost = ref<PostResource | null>(null)
  const populars = ref<PostResource[]>([])
  const meta = ref<ResourceCollectionMeta | null>(null)
  const links = ref<ResourcePagination | null>(null)

  const fetchPosts = async () => {
    const res = await api<ResourceCollection<PostResource>>('/api/posts')

    list.value = res.data
    meta.value = res.meta
    links.value = res.links
  }

  const fetchPost = async (slug: string) => {
    const res = await api<ResourceItem<PostResource>>(`/api/posts/${slug}`)

    currentPost.value = res.data

    return res.data
  }

  const fetchPopulars = async () => {
    const res = await api<ResourceCollection<PostResource>>('/api/posts/populars')

    populars.value = res.data
  }

  return {
    fetchPost,
    fetchPosts,
    fetchPopulars,
    list,
    currentPost,
    meta,
    links,
    populars,
  }
})
