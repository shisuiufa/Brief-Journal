<script setup lang="ts">

import UserProfileCard from "~/components/users/UserProfileCard.vue";
import {Roles} from "~/resources/role";

definePageMeta({
  middleware: 'role',
  roles: [Roles.Admin, Roles.SuperAdmin],
})

const route = useRoute()
const userStore = useUserStore()

const userId = computed(() => String(route.params.id))

const { data: userResponse } = await useAsyncData(
    () => `user-${userId.value}`,
    () => userStore.fetchUser(userId.value),
)

const user = computed(() => userResponse.value?.data ?? null)
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
      </template>
    </UPageHeader>

    <UPageBody>
      <UserProfileCard v-if="user" :user="user" />
    </UPageBody>
  </UPage>
</template>