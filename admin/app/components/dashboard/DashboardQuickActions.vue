<script setup lang="ts">
const quickActions = [
  {
    label: 'Create post',
    description: 'Write a new article',
    icon: 'i-lucide-circle-plus',
    to: '/posts/create',
  },
  {
    label: 'Manage posts',
    description: 'Edit drafts and published posts',
    icon: 'i-lucide-newspaper',
    to: '/posts',
  },
  {
    label: 'Manage users',
    description: 'Control admin access',
    icon: 'i-lucide-users',
    to: '/users',
  },
]
</script>

<template>
  <UCard>
    <template #header>
      <div>
        <h2 class="font-semibold">
          Quick actions
        </h2>

        <p class="text-sm text-muted">
          Common admin tasks.
        </p>
      </div>
    </template>

    <div class="space-y-2">
      <UButton
          v-for="action in quickActions"
          :key="action.label"
          :to="action.to"
          color="neutral"
          variant="ghost"
          block
          class="justify-start"
      >
        <template #leading>
          <div class="size-9 rounded-lg bg-elevated flex items-center justify-center">
            <UIcon
                :name="action.icon"
                class="size-4"
            />
          </div>
        </template>

        <div class="text-left">
          <p class="font-medium">
            {{ action.label }}
          </p>

          <p class="text-xs text-muted">
            {{ action.description }}
          </p>
        </div>
      </UButton>
    </div>
  </UCard>
</template>