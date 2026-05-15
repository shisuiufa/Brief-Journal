<script setup lang="ts">
import UiBadge from '@/components/ui/UiBadge.vue';
import UiCard from '@/components/ui/UiCard.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { RouterLink } from 'vue-router';

defineProps<{
  requestedPath: string;
}>();
</script>

<template>
  <section class="mx-auto flex w-full max-w-245 items-center py-8 xl:min-h-[calc(100dvh-260px)]">
    <UiCard class="w-full overflow-hidden">
      <div class="grid grid-cols-1">
        <div class="p-6 md:p-10">
          <UiBadge>404</UiBadge>

          <h1 class="mt-5 max-w-2xl text-4xl font-black leading-tight text-foreground md:text-6xl">
            Page not found
          </h1>

          <p class="mt-5 max-w-2xl text-base leading-8 text-muted">
            The page may have been moved, unpublished, or the address may contain a typo.
          </p>

          <div class="mt-8 rounded-2xl border border-default bg-accent-soft p-4">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-muted">
              Requested path
            </p>
            <p class="break-all text-sm font-semibold text-foreground">
              {{ requestedPath }}
            </p>
          </div>

          <RouterLink
            to="/"
            class="mt-8 inline-flex items-center gap-2 rounded-full bg-accent px-5 py-3 text-sm font-bold text-accent-foreground shadow-soft transition hover:scale-[1.02]"
          >
            <ArrowLeftIcon class="size-4" />
            Back to journal
          </RouterLink>
        </div>
      </div>
    </UiCard>
  </section>
</template>
