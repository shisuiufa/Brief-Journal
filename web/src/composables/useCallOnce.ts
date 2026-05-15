import { getCurrentInstance, inject, onMounted, onServerPrefetch } from 'vue';
import { CallOnceKey, executeCallOnce } from '@/plugins/call-once';
import type { CallOnce } from '@/types/call-once';

export function useCallOnce(): CallOnce {
  const context = inject(CallOnceKey);

  if (!context) {
    throw new Error('CallOnce plugin is not installed');
  }

  return <T>(key: string, callback: () => T | Promise<T>) => {
    const execute = () => executeCallOnce(context, key, callback);

    if (!getCurrentInstance()) {
      return execute();
    }

    if (import.meta.env.SSR) {
      onServerPrefetch(execute);

      return Promise.resolve(undefined);
    }

    onMounted(() => {
      void execute();
    });

    return Promise.resolve(undefined);
  };
}
