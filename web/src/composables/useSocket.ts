import { inject } from 'vue'
import { socketKey } from '@/plugins/socket'

export const useSocket = () => {
  return inject(socketKey, null)
}
