/// <reference types="vite/client" />
import type { CallOnce } from '@/types/call-once'
import type { InitialState } from '@/types/initial-state'

interface ImportMetaEnv {
  readonly API_BASE_URL?: string
  readonly VITE_API_BASE_URL?: string
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}

declare global {
  interface Window {
    __INITIAL_STATE__?: InitialState
  }
}

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $callOnce: CallOnce
  }
}


export {}
