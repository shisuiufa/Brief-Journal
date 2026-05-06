<script setup lang="ts">
import { RoleFilter } from "~/resources/role";

const userStore = useUserStore();

const { search, role } = storeToRefs(userStore);

const roleItems = [
  {
    label: "All roles",
    value: RoleFilter.All,
  },
  {
    label: "Super admin",
    value: RoleFilter.SuperAdmin,
  },
  {
    label: "Admin",
    value: RoleFilter.Admin,
  },
  {
    label: "Editor",
    value: RoleFilter.Editor,
  },
  {
    label: "User",
    value: RoleFilter.User,
  },
];
</script>

<template>
  <UCard>
    <div
      class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
      <UInput
        v-model="search"
        icon="i-lucide-search"
        placeholder="Search users..."
        class="sm:max-w-xs"
      />

      <USelect v-model="role" :items="roleItems" class="sm:w-52" />
    </div>
  </UCard>
</template>
