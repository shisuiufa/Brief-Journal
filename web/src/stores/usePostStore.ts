import { useApi } from '@/composables/useApi'
import type { PostResource } from '@/resources/post'
import {
  type ResourceCollection,
  type ResourceItem,
  type ResourceCollectionMeta,
  type ResourcePagination,
  ApiError,
} from '@/types/api'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { ApiHttpError } from '@/errors/ApiHttpError.ts'

export const usePostStore = defineStore('post', () => {
  const api = useApi()

  const list = ref<PostResource[]>([])
  const currentPost = ref<PostResource | null>(null)
  const populars = ref<PostResource[]>([])
  const meta = ref<ResourceCollectionMeta | null>(null)
  const links = ref<ResourcePagination | null>(null)

  const currentPostNotFoundSlug = ref<string | null>(null)

  const fetchPosts = async () => {
    const res = await api<ResourceCollection<PostResource>>('/api/posts')

    list.value = res.data
    meta.value = res.meta
    links.value = res.links
  }

  const fetchPost = async (slug: string) => {
    try {
      const res = await api<ResourceItem<PostResource>>(`/api/posts/${slug}`)

      currentPost.value = res.data
      currentPostNotFoundSlug.value = null

      return res.data
    } catch (error: unknown) {
      if (error instanceof ApiHttpError && error.status === ApiError.NotFound) {
        currentPost.value = null
        currentPostNotFoundSlug.value = slug

        return null
      }
      throw error
    }
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
    meta,
    links,
    populars,
    currentPost,
    currentPostNotFoundSlug,
  }
})
