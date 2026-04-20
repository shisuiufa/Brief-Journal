<script setup lang="ts">
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'

defineProps<{
  direction: 'prev' | 'next'
  disabled?: boolean
}>()
</script>

<template>
  <button
    type="button"
    class="bg-card cursor-pointer border-default text-foreground hover:bg-accent-soft hover:text-accent duration-300 ease-out hover:-translate-y-1 inline-flex size-8 md:size-11 items-center justify-center rounded-xl md:rounded-2xl border transition disabled:pointer-events-none disabled:opacity-40"
    :disabled="disabled"
    :aria-label="direction === 'prev' ? 'Previous page' : 'Next page'"
  >
    <ChevronLeftIcon v-if="direction === 'prev'" class="size-4 lg:size-5" />
    <ChevronRightIcon v-else class="size-4 lg:size-5" />
  </button>
</template>
