<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import UiSkeleton from '@/components/ui/UiSkeleton.vue'
</script>

<template>
  <aside>
    <UiCard class="mb-4 overflow-hidden px-6 py-5 xl:mb-8" as="section" hover="">
      <div class="mb-5 flex items-start justify-between gap-4">
        <div class="flex-1">
          <UiSkeleton class="mb-3 h-6 w-40" />
          <UiSkeleton class="h-4 w-full" />
          <UiSkeleton class="mt-2 h-4 w-10/12" />
        </div>

        <UiSkeleton class="h-7 w-16 rounded-full" />
      </div>

      <div class="flex flex-col gap-6">
        <div v-for="item in 4" :key="item" class="flex gap-3">
          <UiSkeleton class="h-7 w-7 shrink-0 rounded-full" />
          <div class="flex-1 space-y-2">
            <UiSkeleton class="h-4 w-full" />
            <UiSkeleton class="h-3 w-3/5" />
          </div>
        </div>
      </div>
    </UiCard>

    <UiCard class="overflow-hidden px-6 py-5" as="section" hover="">
      <div class="mb-5 flex items-start justify-between gap-4">
        <div class="flex-1">
          <UiSkeleton class="mb-3 h-6 w-36" />
          <UiSkeleton class="h-4 w-full" />
          <UiSkeleton class="mt-2 h-4 w-9/12" />
        </div>

        <UiSkeleton class="h-7 w-12 rounded-full" />
      </div>

      <div class="flex flex-wrap gap-2">
        <UiSkeleton class="h-8 w-16 rounded-full" />
        <UiSkeleton class="h-8 w-20 rounded-full" />
        <UiSkeleton class="h-8 w-24 rounded-full" />
        <UiSkeleton class="h-8 w-14 rounded-full" />
        <UiSkeleton class="h-8 w-20 rounded-full" />
      </div>
    </UiCard>
  </aside>
</template>
