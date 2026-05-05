<script setup lang="ts">

import UserProfileCard from "~/components/users/UserProfileCard.vue";

const route = useRoute()

const userId = computed(() => String(route.params.id))

// Потом заменишь на API:
// const { data: user } = await useFetch(`/api/admin/users/${userId.value}`)

const user = {
  id: userId.value,
  name: 'John Doe',
  email: 'john@example.com',
  role: 'editor' as const,
  createdAt: 'Apr 26, 2026',
  updatedAt: 'Apr 30, 2026',
}
</script>

<template>
  <UPage>
    <UPageHeader
        title="User profile"
        description="View user account details and access role."
    >
      <template #links>
        <UButton
            to="/users"
            icon="i-lucide-arrow-left"
            color="neutral"
            variant="ghost"
        >
          Back to users
        </UButton>

        <UButton
            :to="`/users/${userId}/edit`"
            icon="i-lucide-pencil"
        >
          Edit user
        </UButton>
      </template>
    </UPageHeader>

    <UPageBody>
      <UserProfileCard :user="user" />
    </UPageBody>
  </UPage>
</template>