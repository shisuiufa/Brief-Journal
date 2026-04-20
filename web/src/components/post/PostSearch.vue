<script setup lang="ts">
import UiInput from '@/components/ui/UiInput.vue'
import { ref } from 'vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'

const search = ref('')
</script>

<template>
  <section class="w-full">
    <div class="mb-4 space-y-1">
      <h2 class="text-foreground text-2xl font-semibold leading-tight">
        Find the next article faster
      </h2>
      <p class="text-muted text-sm leading-6">
        Search by title, topic, or keyword to jump straight into the stories you need.
      </p>
    </div>

    <UiInput
      class="w-full"
      v-model="search"
      placeholder="Search by topic, title, or keyword..."
      type="search"
    >
      <template #icon>
        <MagnifyingGlassIcon class="text-muted size-5" />
      </template>
    </UiInput>
  </section>
</template>
