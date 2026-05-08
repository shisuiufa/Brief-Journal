<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import UiSkeleton from '@/components/ui/UiSkeleton.vue'
</script>

<template>
  <UiCard
    class="w-full p-5 mb-4 xl:mb-8 grid grid-cols-1 xl:grid-cols-2 items-start justify-between gap-4 xl:gap-8"
    hover=""
  >
    <section class="w-full">
      <UiSkeleton class="mb-3 h-7 w-64 max-w-full" />
      <UiSkeleton class="mb-4 h-4 w-full max-w-md" />
      <UiSkeleton class="h-11 w-full rounded-xl" />
    </section>

    <div class="flex flex-wrap justify-center gap-2 px-2">
      <UiSkeleton class="h-9 w-14 rounded-full" />
      <UiSkeleton class="h-9 w-20 rounded-full" />
      <UiSkeleton class="h-9 w-28 rounded-full" />
      <UiSkeleton class="h-9 w-24 rounded-full" />
      <UiSkeleton class="h-9 w-24 rounded-full" />
    </div>
  </UiCard>
</template>
