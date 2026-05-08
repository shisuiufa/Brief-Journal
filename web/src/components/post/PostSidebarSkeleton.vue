<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import UiSkeleton from '@/components/ui/UiSkeleton.vue'
</script>

<template>
  <aside class="space-y-4 xl:sticky xl:top-28 xl:self-start">
    <UiCard class="p-5" hover="">
      <UiSkeleton class="mb-4 h-3 w-32" />

      <div class="space-y-4">
        <div class="space-y-2">
          <UiSkeleton class="h-4 w-20" />
          <UiSkeleton class="h-4 w-28" />
        </div>

        <div class="space-y-2">
          <UiSkeleton class="h-4 w-24" />
          <UiSkeleton class="h-4 w-20" />
        </div>

        <div class="space-y-2">
          <UiSkeleton class="h-4 w-16" />
          <UiSkeleton class="h-4 w-24" />
        </div>
      </div>
    </UiCard>

    <UiCard class="p-5" hover="">
      <UiSkeleton class="mb-4 h-3 w-20" />

      <div class="flex flex-wrap gap-2">
        <UiSkeleton class="h-7 w-16 rounded-full" />
        <UiSkeleton class="h-7 w-20 rounded-full" />
        <UiSkeleton class="h-7 w-14 rounded-full" />
      </div>
    </UiCard>
  </aside>
</template>
