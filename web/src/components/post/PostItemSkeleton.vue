<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue';
import UiSkeleton from '@/components/ui/UiSkeleton.vue';
</script>

<template>
  <UiCard class="block p-5 xl:p-8" hover="">
    <UiSkeleton class="mb-3 h-64 w-full rounded-2xl xl:h-80" />

    <div class="mb-3 flex min-h-7 flex-wrap items-center gap-2">
      <UiSkeleton class="h-7 w-20 rounded-full" />
      <UiSkeleton class="h-7 w-24 rounded-full" />
    </div>

    <div class="mb-3 space-y-2">
      <UiSkeleton class="h-6 w-full" />
      <UiSkeleton class="h-6 w-3/4" />
    </div>

    <div class="mb-5 space-y-2">
      <UiSkeleton class="h-4 w-full" />
      <UiSkeleton class="h-4 w-11/12" />
      <UiSkeleton class="h-4 w-8/12" />
    </div>

    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <UiSkeleton class="size-10 rounded-full" />
        <div class="space-y-2">
          <UiSkeleton class="h-4 w-28" />
          <UiSkeleton class="h-3 w-20" />
        </div>
      </div>

      <UiSkeleton class="h-4 w-16" />
    </div>
  </UiCard>
</template>
