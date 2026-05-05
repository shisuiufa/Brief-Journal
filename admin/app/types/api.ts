import type { FetchOptions, ResponseType  } from 'ofetch'

export interface ResourceItem<T> {
  data: T | null
}

export interface ApiMessage {
  message: string
}

export interface ApiMessageResource<TData> extends ApiMessage {
  data: TData
}

export interface ApiPaginationLink {
  url: string | null
  label: string
  active: boolean
}

export interface ResourcePagination {
  first: string
  last: string
  prev: string | null
  next: string | null
}

export interface ResourceCollectionMeta {
  current_page: number
  from: number | null
  last_page: number
  links: ApiPaginationLink[]
  path: string
  per_page: number
  to: number | null
  total: number
}

export interface ResourceCollection<TData> {
  data: TData[]
  links: ResourcePagination
  meta: ResourceCollectionMeta
}

export interface CollectionQueryFilter {
  message: string
  errors: Record<string, string[]>
}

export enum ApiError {
  BadRequest = 400,
  UnprocessableContent = 422,
  Unauthorized = 401,
  Forbidden = 403,
  PageExpired = 419,
}

export type ApiMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'

export type ApiRequestOptions<TResponse = unknown> = Omit<
    FetchOptions<ResponseType, TResponse>,
    'method'
> & {
  method?: ApiMethod
}