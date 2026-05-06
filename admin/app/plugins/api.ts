import { useApiPath } from '~/config/entrypoint';
import {ApiError, type ApiRequestOptions, type ResourceItem} from '~/types/api'
import {useUserSession} from "~/composables/useUserSession";
import type { FetchError } from 'ofetch'
import type {TokenResource} from "~/resources/user";

export default defineNuxtPlugin(() => {
  const { clearUser } = useUserSession();
  const { bearerToken, needsRefresh, setToken, clearToken } = useBearerToken()

  let refreshRequest: Promise<void> | null = null

  const skipRefreshUrls = new Set([
    '/api/auth/login',
    '/api/auth/refresh',
  ])

  const setClientHeaders = (
      clientHeaders?: HeadersInit,
      body?: unknown,
  ): HeadersInit => {
    const headers = new Headers(clientHeaders)

    headers.set('Accept', 'application/json')

    if (!(body instanceof FormData)) {
      headers.set('Content-Type', 'application/json')
    }

    if (bearerToken.value) {
      headers.set('Authorization', bearerToken.value)
    }

    return headers
  };

  const logoutLocally = () => {
    clearUser()
    clearToken()

    navigateTo('/login')
  }

  const refreshToken = async () => {
    refreshRequest ??= $fetch<ResourceItem<{ token: TokenResource }>>(`${useApiPath()}/api/auth/refresh`, {
      method: 'POST',
      credentials: 'include',
      headers: setClientHeaders(),
    })
        .then((response) => {
          const token = response.data?.token
          if (token) {
            setToken(
                token.access_token,
               token.expires_in
            )
          }
        })
        .finally(() => {
          refreshRequest = null
        })

    await refreshRequest
  }

  const ensureFreshToken = async (url: string) => {
    if (skipRefreshUrls.has(url)) {
      return
    }

    if (!bearerToken.value || !needsRefresh.value) {
      return
    }

    try {
      await refreshToken()
    } catch {
      logoutLocally()

      throw new Error('Token refresh failed')
    }
  }

  const api = async <TResponse>(
      url: string,
      options: ApiRequestOptions<TResponse> = {}
  ): Promise<TResponse> => {
    const { headers, method = 'GET', responseType = 'json', ...fetchOptions } = options

    try {
      await ensureFreshToken(url)

      const clientHeaders = setClientHeaders(headers, fetchOptions.body)

      return await $fetch<TResponse>(`${useApiPath()}${url}`, {
        ...fetchOptions,
        method,
        headers: clientHeaders,
        credentials: 'include',
        responseType,
      })

    } catch (error) {
      const fetchError = error as FetchError
      const status = fetchError.response?.status

      if (
          status === ApiError.Unauthorized ||
          status === ApiError.PageExpired
      ) {
         logoutLocally();
      }

      throw error
    }
  };

  return {
    provide: {
      api,
    }
  }
})
