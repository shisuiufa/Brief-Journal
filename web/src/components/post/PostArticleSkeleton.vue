<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import UiSkeleton from '@/components/ui/UiSkeleton.vue'
</script>

<template>
  <div class="min-w-0">
    <UiCard class="overflow-hidden" hover="">
      <div class="relative aspect-video min-h-64 overflow-hidden bg-button-tag">
        <div class="absolute bottom-0 left-0 right-0 p-5 xl:p-8">
          <div class="mb-4 flex flex-wrap gap-2">
            <UiSkeleton class="h-6 w-20 rounded-full" />
            <UiSkeleton class="h-6 w-24 rounded-full" />
          </div>

          <div class="max-w-4xl space-y-3">
            <UiSkeleton class="h-9 w-full md:h-12" />
            <UiSkeleton class="h-9 w-4/5 md:h-12" />
          </div>
        </div>
      </div>

      <div class="p-5 xl:p-8">
        <div
          class="mb-8 flex flex-col gap-5 border-b border-default pb-6 md:flex-row md:items-center md:justify-between"
        >
          <div class="flex items-center gap-3">
            <UiSkeleton class="size-12 rounded-full" />
            <div class="space-y-2">
              <UiSkeleton class="h-4 w-32" />
              <UiSkeleton class="h-3 w-24" />
            </div>
          </div>

          <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <UiSkeleton class="h-4 w-24" />
            <UiSkeleton class="h-4 w-24" />
            <UiSkeleton class="h-4 w-20" />
          </div>
        </div>

        <div class="mb-8 max-w-3xl space-y-3">
          <UiSkeleton class="h-6 w-full" />
          <UiSkeleton class="h-6 w-11/12" />
        </div>

        <div class="max-w-3xl space-y-4">
          <UiSkeleton class="h-4 w-full" />
          <UiSkeleton class="h-4 w-full" />
          <UiSkeleton class="h-4 w-10/12" />
          <UiSkeleton class="h-4 w-full" />
          <UiSkeleton class="h-4 w-9/12" />
          <UiSkeleton class="mt-8 h-7 w-2/5" />
          <UiSkeleton class="h-4 w-full" />
          <UiSkeleton class="h-4 w-11/12" />
          <UiSkeleton class="h-4 w-8/12" />
        </div>
      </div>
    </UiCard>
  </div>
</template>
