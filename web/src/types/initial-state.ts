import type { StateTree } from 'pinia'

export type InitialState = {
  pinia?: Record<string, StateTree>
  callOnce?: string[]
}
