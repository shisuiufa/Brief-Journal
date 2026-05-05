import type { ApiRequestOptions } from '~/types/api'

export const useApi = <TResponse>(
  request: string,
  options?: ApiRequestOptions<TResponse>
) => {
  const { $api } = useNuxtApp();

  return $api<TResponse>(request, options)
}