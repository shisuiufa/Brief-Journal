<script setup lang="ts">
import { SunIcon, MoonIcon } from '@heroicons/vue/24/outline'
import { useTheme } from '@/composables/useTheme.ts'

const { toggleTheme, mode } = useTheme()
</script>

<template>
  <button
    type="button"
    @click="toggleTheme"
    class="bg-button-tag border border-default shadow-soft text-foreground inline-flex cursor-pointer items-center justify-center rounded-full p-2 transition hover:scale-[1.03] hover:bg-accent-soft hover:text-accent"
    :aria-label="mode === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
  >
    <SunIcon v-if="mode != 'light'" class="size-5" />
    <MoonIcon v-else class="size-5" />
  </button>
</template>
