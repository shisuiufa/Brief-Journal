<script setup lang="ts">
import UsersFilters from '~/components/users/UsersFilters.vue'
import UsersTable from "~/components/users/table/UsersTable.vue";

const userStore = useUserStore();

const { list } = storeToRefs(userStore);

const { pending } = await useLazyAsyncData(
    'users',
    () => userStore.fetchUsers(),
)
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
        <UsersTable
            :users="list ?? []"
            :loading="pending"
        />
      </div>
    </UPageBody>
  </UPage>
</template>