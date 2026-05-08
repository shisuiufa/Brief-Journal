export type CallOnceContext = {
  called: Set<string>
  pending: Map<string, Promise<unknown>>
}

export type CallOnce = <T>(key: string, callback: () => T | Promise<T>) => Promise<T | undefined>
