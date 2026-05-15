import type { Socket } from "socket.io-client";

export const useSocket = (): Socket => {
  const { $socket } = useNuxtApp();

  return $socket;
};
