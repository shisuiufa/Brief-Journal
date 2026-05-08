import type { App, InjectionKey } from 'vue'
import type { CallOnceContext } from '@/types/call-once'

export const CallOnceKey: InjectionKey<CallOnceContext> = Symbol('CallOnce')

export function createCallOnceContext(initialKeys: string[] = []): CallOnceContext {
  return {
    called: new Set(initialKeys),
    pending: new Map(),
  }
}

export async function executeCallOnce<T>(
  context: CallOnceContext,
  key: string,
  callback: () => T | Promise<T>,
): Promise<T | undefined> {
  if (context.called.has(key)) {
    return undefined
  }

  const pending = context.pending.get(key)

  if (pending) {
    return (await pending) as T
  }

  const promise = Promise.resolve(callback())

  context.pending.set(key, promise)

  try {
    const result = await promise

    context.called.add(key)

    return result
  } finally {
    context.pending.delete(key)
  }
}

export function createCallOncePlugin(context: CallOnceContext) {
  return {
    install(app: App) {
      app.provide(CallOnceKey, context)

      app.config.globalProperties.$callOnce = <T>(key: string, callback: () => T | Promise<T>) => {
        return executeCallOnce(context, key, callback)
      }
    },
  }
}

export function getCallOnceKeys(context: CallOnceContext): string[] {
  return Array.from(context.called)
}
