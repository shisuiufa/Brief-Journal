<script setup lang="ts">
import UsersFilters from '~/components/users/UsersFilters.vue'
import UsersTable from "~/components/users/table/UsersTable.vue";
</script>

<template>
  <UPage>
    <UPageHeader
        title="Users"
        description="Manage admin users, roles and access."
    >
      <template #links>
        <UButton
            to="/users/create"
            icon="i-lucide-user-plus"
        >
          Create user
        </UButton>
      </template>
    </UPageHeader>

    <UPageBody>
      <div class="space-y-4">
        <UsersFilters />
        <UsersTable />
      </div>
    </UPageBody>
  </UPage>
</template>