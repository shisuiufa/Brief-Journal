import { onMounted, onUnmounted, type ComputedRef } from 'vue'
import { useSocket } from '@/composables/useSocket'
import { RealtimeEventEnum } from '@/resources/realtime'
import { usePostStore } from '@/stores/usePostStore'
import type { PostRealtimePayload } from '@/types/realtime.ts'

export const useCurrentPostRealtime = (slug: ComputedRef<string>) => {
  const socket = useSocket()
  const postStore = usePostStore()

  const refreshCurrentPost = async (payload: PostRealtimePayload) => {
    if (payload.slug !== slug.value) {
      return
    }

    await postStore.fetchPost(slug.value)
  }

  onMounted(() => {
    if (!socket) {
      return
    }
    socket.on(RealtimeEventEnum.PostUpdated, refreshCurrentPost)
    socket.on(RealtimeEventEnum.PostDeleted, refreshCurrentPost)
  })

  onUnmounted(() => {
    if (!socket) {
      return
    }
    socket.off(RealtimeEventEnum.PostUpdated, refreshCurrentPost)
    socket.off(RealtimeEventEnum.PostDeleted, refreshCurrentPost)
  })
}
