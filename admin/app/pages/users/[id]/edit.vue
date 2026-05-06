<script setup lang="ts">
import UserForm from '~/components/users/form/UserForm.vue'
import {Roles} from "~/resources/role";

definePageMeta({
  middleware: 'role',
  roles: [Roles.Admin, Roles.SuperAdmin],
})

const route = useRoute()
const userStore = useUserStore()

const userId = computed(() => String(route.params.id))

const { data: userResponse, error } = await useAsyncData(
    () => `user-${userId.value}`,
    () => userStore.fetchUser(userId.value),
)

const user = computed(() => userResponse.value?.data ?? null)

if (error.value || !user.value) {
  await navigateTo('/users')
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
          v-if="user"
          mode="edit"
          :user="user"
      />
    </UPageBody>
  </UPage>
</template>
