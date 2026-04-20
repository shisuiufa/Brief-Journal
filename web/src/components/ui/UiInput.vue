<script setup lang="ts">
import { computed } from 'vue'

const model = defineModel<string>()

const props = withDefaults(
  defineProps<{
    textSize?: string
    iconSize?: string
    placeholder?: string
    type?: string
  }>(),
  {
    textSize: 'text-sm',
    iconSize: 'size-4',
    type: 'text',
  },
)

const inputClass = computed(() => [
  'text-foreground placeholder:text-muted w-full bg-transparent outline-none',
  props.textSize,
])

const iconClass = computed(() => ['text-muted shrink-0', props.iconSize])
</script>

<template>
  <label
    class="bg-button-tag border border-default shadow-soft ring-theme flex w-full items-center gap-3 rounded-2xl px-4 py-3 transition focus-within:bg-card focus-within:ring-4"
  >
    <slot name="icon" :class="iconClass" />

    <input v-model="model" :class="inputClass" :placeholder="placeholder" :type="type" />
  </label>
</template>
