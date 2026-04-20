<script setup lang="ts">
withDefaults(
  defineProps<{
    as?: string
    hover?: string
  }>(),
  {
    as: 'div',
    hover: 'hover:-translate-y-1',
  },
)
</script>

<template>
  <component
    :is="as"
    :class="[hover]"
    class="bg-card border border-default shadow-card rounded-2xl transition duration-300 ease-out"
  >
    <slot />
  </component>
</template>
