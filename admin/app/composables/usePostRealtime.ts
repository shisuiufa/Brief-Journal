export const usePostRealtime = () => {
  const socket = useSocket();
  const postStore = usePostStore();

  const refreshPosts = async () => {
    await postStore.fetchPosts();
  };

  onMounted(() => {
    socket.on("post.published", refreshPosts);
    socket.on("post.updated", refreshPosts);
    socket.on("post.deleted", refreshPosts);
  });

  onUnmounted(() => {
    socket.off("post.published", refreshPosts);
    socket.off("post.updated", refreshPosts);
    socket.off("post.deleted", refreshPosts);
  });
};
