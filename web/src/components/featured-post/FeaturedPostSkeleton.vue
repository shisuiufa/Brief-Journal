<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import UiSkeleton from '@/components/ui/UiSkeleton.vue'
</script>

<template>
  <UiCard class="p-5 xl:p-8" hover="">
    <div class="xl:flex gap-4 md:gap-8">
      <UiSkeleton class="mb-3 h-56 w-full rounded-2xl xl:mb-0 xl:w-2xs" />

      <div class="flex min-h-56 flex-1 flex-col justify-between">
        <div>
          <UiSkeleton class="mb-3 h-4 w-24" />

          <div class="mb-4 space-y-3">
            <UiSkeleton class="h-8 w-full" />
            <UiSkeleton class="h-8 w-4/5" />
          </div>

          <div class="space-y-2">
            <UiSkeleton class="h-4 w-full" />
            <UiSkeleton class="h-4 w-11/12" />
            <UiSkeleton class="h-4 w-8/12" />
          </div>
        </div>
      </div>
    </div>
  </UiCard>
</template>
