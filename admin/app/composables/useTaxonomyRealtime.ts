import { RealtimeEventEnum } from "~/resources/realtime";

export const useTaxonomyRealtime = () => {
  const socket = useSocket();
  const categoryStore = useCategoryStore();
  const tagStore = useTagStore();

  const refreshCategories = async () => {
    await categoryStore.fetchCategories();
  };

  const refreshTags = async () => {
    await tagStore.fetchTags();
  };

  onMounted(() => {
    socket.on(RealtimeEventEnum.CategoryCreated, refreshCategories);
    socket.on(RealtimeEventEnum.CategoryDeleted, refreshCategories);
    socket.on(RealtimeEventEnum.CategoryUpdated, refreshCategories);
    socket.on(RealtimeEventEnum.TagCreated, refreshTags);
    socket.on(RealtimeEventEnum.TagUpdated, refreshTags);
    socket.on(RealtimeEventEnum.TagDeleted, refreshTags);
  });

  onUnmounted(() => {
    socket.off(RealtimeEventEnum.CategoryCreated, refreshCategories);
    socket.off(RealtimeEventEnum.CategoryDeleted, refreshCategories);
    socket.off(RealtimeEventEnum.CategoryUpdated, refreshCategories);
    socket.off(RealtimeEventEnum.TagCreated, refreshTags);
    socket.off(RealtimeEventEnum.TagUpdated, refreshTags);
    socket.off(RealtimeEventEnum.TagDeleted, refreshTags);
  });
};
