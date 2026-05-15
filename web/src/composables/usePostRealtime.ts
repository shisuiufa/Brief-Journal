import { type ComputedRef, onMounted, onUnmounted } from 'vue'
import { RealtimeEventEnum } from '@/resources/realtime'
import { useSocket } from '@/composables/useSocket'
import { usePostStore } from '@/stores/usePostStore.ts'
import type { ApiQuery } from '@/types/api.ts'

export const usePostRealtime = (query: ComputedRef<ApiQuery>) => {
  const socket = useSocket()
  const postStore = usePostStore()
  const refreshPosts = async () => {
    await postStore.fetchPosts(query.value)
    await postStore.fetchFeatured()
    await postStore.fetchPopulars()
  }

  onMounted(() => {
    if (!socket) {
      return
    }
    socket.on(RealtimeEventEnum.PostPublished, refreshPosts)
    socket.on(RealtimeEventEnum.PostUpdated, refreshPosts)
    socket.on(RealtimeEventEnum.PostDeleted, refreshPosts)
  })

  onUnmounted(() => {
    if (!socket) {
      return
    }
    socket.off(RealtimeEventEnum.PostPublished, refreshPosts)
    socket.off(RealtimeEventEnum.PostUpdated, refreshPosts)
    socket.off(RealtimeEventEnum.PostDeleted, refreshPosts)
  })
}
