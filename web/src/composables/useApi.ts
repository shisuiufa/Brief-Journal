import { apiKey } from '@/plugins/api'
import type { ApiRequestOptions } from '@/types/api'
import { inject } from 'vue'

export const useApi = <TResponse>(request: string, options?: ApiRequestOptions) => {
  const api = inject(apiKey)

  if (!api) {
    throw new Error('API plugin is not installed')
  }

  return api<TResponse>(request, options)
}
