<script setup lang="ts">
const search = ref('')
const status = ref('all')

const statusItems = [
  {
    label: 'All',
    value: 'all',
  },
  {
    label: 'Published',
    value: 'published',
  },
  {
    label: 'Draft',
    value: 'draft',
  },
]
</script>

<template>
  <UCard>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <UInput
          v-model="search"
          icon="i-lucide-search"
          placeholder="Search posts..."
          class="sm:max-w-xs"
      />

      <USelect
          v-model="status"
          :items="statusItems"
          class="sm:w-48"
      />
    </div>
  </UCard>
</template>