import { RealtimeEventEnum } from "~/resources/realtime";

export const useUserRealtime = () => {
  const socket = useSocket();
  const userStore = useUserStore();

  const refreshUsers = async () => {
    await userStore.fetchUsers();
  };

  onMounted(() => {
    socket.on(RealtimeEventEnum.UserCreated, refreshUsers);
    socket.on(RealtimeEventEnum.UserUpdated, refreshUsers);
    socket.on(RealtimeEventEnum.UserDeleted, refreshUsers);
  });

  onUnmounted(() => {
    socket.off(RealtimeEventEnum.UserCreated, refreshUsers);
    socket.off(RealtimeEventEnum.UserUpdated, refreshUsers);
    socket.off(RealtimeEventEnum.UserDeleted, refreshUsers);
  });
};
