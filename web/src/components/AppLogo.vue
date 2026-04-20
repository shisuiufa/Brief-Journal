<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <RouterLink
    to="/"
    class="flex justify-center items-center gap-3 rounded-full pr-2 transition hover:opacity-95"
    aria-label="Go to homepage"
  >
    <span
      class="bg-accent text-accent-foreground shadow-soft inline-flex h-11 w-11 items-center justify-center rounded-2xl text-base font-black"
    >
      BR
    </span>

    <span class="flex flex-col leading-none">
      <span class="text-foreground text-lg font-black tracking-[0.18em] uppercase leading-none"
        >Brief</span
      >
      <span
        class="text-muted group-hover:text-accent mt-1 text-[0.68rem] font-semibold tracking-[0.24em] uppercase transition"
      >
        Journal
      </span>
    </span>
  </RouterLink>
</template>
