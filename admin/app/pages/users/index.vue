<script setup lang="ts">
import UsersFilters from '~/components/users/UsersFilters.vue'
import UsersTable from "~/components/users/table/UsersTable.vue";
import {Roles} from "~/resources/role";

definePageMeta({
  middleware: 'role',
  roles: [Roles.Admin, Roles.SuperAdmin],
})

const toast = useToast()
const userStore = useUserStore();

const { list } = storeToRefs(userStore);

const { pending, refresh } = await useLazyAsyncData(
    'users',
    () => userStore.fetchUsers(),
)

const handleDelete = async (id: number) => {
  try {
    await userStore.destroy(id);
    toast.add({
      title: 'User deleted',
      description: 'The account has been removed.',
      color: 'success',
    })
    await refresh();
  } catch {
    toast.add({
      title: 'Failed to delete user',
      description: 'Please try again.',
      color: 'error',
    })
  }
}
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
            @delete-user="handleDelete"
        />
      </div>
    </UPageBody>
  </UPage>
</template>