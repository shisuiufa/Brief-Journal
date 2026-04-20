<script setup lang="ts">
defineProps<{
  page: number
  active?: boolean
}>()
</script>

<template>
  <button
    type="button"
    class="inline-flex size-8 md:size-11 items-center justify-center rounded-xl md:rounded-2xl border font-semibold transition cursor-pointer duration-300 ease-out hover:-translate-y-1"
    :class="
      active
        ? 'bg-accent text-accent-foreground border-default shadow-soft'
        : 'bg-card border-default text-foreground hover:bg-accent-soft hover:text-accent'
    "
    :aria-current="active ? 'page' : undefined"
    :aria-label="`Go to page ${page}`"
  >
    {{ page }}
  </button>
</template>
