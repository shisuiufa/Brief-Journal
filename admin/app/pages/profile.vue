<script setup lang="ts">
// Потом заменишь на API:
// const { data: profile } = await useFetch('/api/admin/profile')

import ProfileCard from "~/components/profile/ProfileCard.vue";
import ProfilePasswordForm from "~/components/profile/form/ProfilePasswordForm.vue";

const profile = {
  id: 1,
  name: 'Admin',
  email: 'admin@example.com',
  role: 'super-admin' as const,
  createdAt: 'Apr 30, 2026',
}
</script>

<template>
  <UPage>
    <UPageHeader
        title="Profile"
        description="Manage your account information and password."
    />

    <UPageBody>
      <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
        <ProfileCard :profile="profile" />

        <ProfilePasswordForm />
      </div>
    </UPageBody>
  </UPage>
</template>