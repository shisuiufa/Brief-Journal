import type { App, InjectionKey } from 'vue'
import { useApiPath } from '@/config/entrypoint'
import type { ApiRequestBody, ApiRequestOptions, ApiQuery } from '@/types/api'
import { ApiHttpError } from '@/errors/ApiHttpError.ts'

export type ApiClient = <TResponse>(url: string, options?: ApiRequestOptions) => Promise<TResponse>

export const apiKey: InjectionKey<ApiClient> = Symbol('api')

type ApiPluginOptions = {
  baseUrl?: string
}

const isJsonBody = (
  body: ApiRequestBody | undefined,
): body is Record<string, unknown> | unknown[] => {
  if (body === null || body === undefined) {
    return false
  }

  if (body instanceof FormData || body instanceof URLSearchParams || body instanceof Blob) {
    return false
  }

  return typeof body === 'object' || Array.isArray(body)
}

const appendQuery = (url: URL, query?: ApiQuery) => {
  if (!query) {
    return
  }

  for (const [key, value] of Object.entries(query)) {
    const values = Array.isArray(value) ? value : [value]

    for (const item of values) {
      if (item === null || item === undefined) {
        continue
      }

      url.searchParams.append(key, String(item))
    }
  }
}

const createRequestUrl = (baseUrl: string, path: string, query?: ApiQuery) => {
  const url = new URL(path, baseUrl)
  appendQuery(url, query)

  return url.toString()
}

const createHeaders = (headers?: HeadersInit, body?: ApiRequestBody | undefined) => {
  const requestHeaders = new Headers(headers)

  requestHeaders.set('Accept', 'application/json')

  if (isJsonBody(body) && !requestHeaders.has('Content-Type')) {
    requestHeaders.set('Content-Type', 'application/json')
  }

  return requestHeaders
}

export const createApiClient = (baseUrl = useApiPath()): ApiClient => {
  return async <TResponse>(url: string, options: ApiRequestOptions = {}) => {
    const { method = 'GET', query, headers, body, ...fetchOptions } = options
    const requestBody = isJsonBody(body) ? JSON.stringify(body) : body

    const response = await fetch(createRequestUrl(baseUrl, url, query), {
      ...fetchOptions,
      method,
      headers: createHeaders(headers, body),
      body: requestBody,
    })

    if (!response.ok) {
      let data: unknown = null

      try {
        data = await response.clone().json()
      } catch {
        // body is empty or not JSON
      }

      throw new ApiHttpError(response, data)
    }

    if (response.status === 204) {
      return null as TResponse
    }

    return (await response.json()) as TResponse
  }
}

export const createApiPlugin = (options: ApiPluginOptions = {}) => {
  return {
    install(app: App) {
      app.provide(apiKey, createApiClient(options.baseUrl))
    },
  }
}
