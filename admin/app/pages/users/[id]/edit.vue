<script setup lang="ts">
import UserForm from '~/components/users/form/UserForm.vue'

const route = useRoute()

const userId = computed(() => String(route.params.id))

// Потом заменишь на API:
// const { data: user } = await useFetch(`/api/admin/users/${userId.value}`)

const user = {
  id: userId.value,
  name: 'John Doe',
  email: 'john@example.com',
  role: 'editor' as const,
}
</script>

<template>
  <UPage>
    <UPageHeader
        title="Edit user"
        description="Update user information and access role."
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
      </template>
    </UPageHeader>

    <UPageBody>
      <UserForm
          mode="edit"
          :user-id="userId"
          :initial-state="user"
      />
    </UPageBody>
  </UPage>
</template>