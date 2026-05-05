<script setup lang="ts">
const search = ref('')
const role = ref('all')

const roleItems = [
  {
    label: 'All roles',
    value: 'all',
  },
  {
    label: 'Super admin',
    value: 'super-admin',
  },
  {
    label: 'Admin',
    value: 'admin',
  },
  {
    label: 'Editor',
    value: 'editor',
  },
  {
    label: 'User',
    value: 'user',
  },
]
</script>

<template>
  <UCard>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <UInput
          v-model="search"
          icon="i-lucide-search"
          placeholder="Search users..."
          class="sm:max-w-xs"
      />

      <USelect
          v-model="role"
          :items="roleItems"
          class="sm:w-52"
      />
    </div>
  </UCard>
</template>