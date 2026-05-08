import { apiKey } from '@/plugins/api'
import { inject } from 'vue'

export const useApi = () => {
  const api = inject(apiKey)

  if (!api) {
    throw new Error('API plugin is not installed')
  }

  return api
}
