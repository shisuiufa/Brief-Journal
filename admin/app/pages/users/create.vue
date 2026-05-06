<script setup lang="ts">
import UserForm from '~/components/users/form/UserForm.vue'
import {Roles} from "~/resources/role";

definePageMeta({
  middleware: 'role',
  roles: [Roles.Admin, Roles.SuperAdmin],
})
</script>

<template>
  <UPage>
    <UPageHeader
        title="Create user"
        description="Create a new admin panel user and assign a role."
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
      <UserForm />
    </UPageBody>
  </UPage>
</template>