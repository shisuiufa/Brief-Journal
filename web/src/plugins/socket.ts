import { io, type Socket } from 'socket.io-client'
import type { App, InjectionKey } from 'vue'

export const socketKey: InjectionKey<Socket> = Symbol('socket')

export const createSocketPlugin = () => {
  return {
    install(app: App) {
      const socket = io(import.meta.env.VITE_SOCKET_URL)

      app.provide(socketKey, socket)
    },
  }
}
