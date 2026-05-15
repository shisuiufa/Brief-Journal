import { RealtimeEventEnum } from "~/resources/realtime";

export const usePostRealtime = () => {
  const socket = useSocket();
  const postStore = usePostStore();

  const refreshPosts = async () => {
    await postStore.fetchPosts();
  };

  onMounted(() => {
    socket.on(RealtimeEventEnum.PostPublished, refreshPosts);
    socket.on(RealtimeEventEnum.PostUpdated, refreshPosts);
    socket.on(RealtimeEventEnum.PostDeleted, refreshPosts);
  });

  onUnmounted(() => {
    socket.off(RealtimeEventEnum.PostPublished, refreshPosts);
    socket.off(RealtimeEventEnum.PostUpdated, refreshPosts);
    socket.off(RealtimeEventEnum.PostDeleted, refreshPosts);
  });
};
