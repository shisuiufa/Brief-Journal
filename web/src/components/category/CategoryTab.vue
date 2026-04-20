<script setup lang="ts">
defineProps<{
  label: string
  active?: boolean
}>()

defineEmits(['select'])
</script>

<template>
  <button
    type="button"
    class="border border-default rounded-2xl px-4 py-2 text-sm font-medium transition hover:-translate-y-0.5 cursor-pointer"
    :class="
      active
        ? 'bg-accent text-accent-foreground shadow-soft'
        : 'bg-button-tag text-foreground hover:bg-accent-soft hover:text-accent'
    "
    @click="$emit('select')"
  >
    {{ label }}
  </button>
</template>
