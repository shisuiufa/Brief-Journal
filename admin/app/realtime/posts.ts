import type { Socket } from "socket.io-client";

export const registerPostRealtime =(socket: Socket) => {
  const postStore = usePostStore();
  
  const refreshPosts = async () => {
    await postStore.fetchPosts();
  };

  socket.on("post.published", refreshPosts);
  socket.on("post.updated", refreshPosts);
  socket.on("post.deleted", refreshPosts);
}