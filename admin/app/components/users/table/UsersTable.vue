<script setup lang="ts">
import type { DropdownMenuItem, TableColumn } from '@nuxt/ui'
import type {UserResource} from "~/resources/user";
import {getRoleColor} from "~/utils/role";

defineProps<{
  users: UserResource[],
  loading?: boolean
}>()

const columns: TableColumn<UserResource>[] = [
  {
    accessorKey: 'name',
    header: 'User',
  },
  {
    accessorKey: 'email',
    header: 'Email',
  },
  {
    accessorKey: 'roles',
    header: 'Roles',
  },
  {
    id: 'actions',
  },
]

const getUserActions = (user: UserResource): DropdownMenuItem[][] => [
  [
    {
      label: 'Edit',
      icon: 'i-lucide-pencil',
      to: `/users/${user.id}/edit`,
    },
    {
      label: 'View profile',
      icon: 'i-lucide-user',
      to: `/users/${user.id}`,
    },
  ],
  [
    {
      label: 'Delete',
      icon: 'i-lucide-trash',
      color: 'error',
    },
  ],
]
</script>

<template>
  <UCard>
    <UTable
        :data="users"
        :columns="columns"
    >
      <template #name-cell="{ row }">
        <div class="flex items-center gap-3">
          <UAvatar
              :alt="row.original.name"
              size="md"
          />

          <div class="min-w-0">
            <p class="font-medium truncate">
              {{ row.original.name }}
            </p>

            <p class="text-sm text-muted truncate">
              {{ row.original.email }}
            </p>
          </div>
        </div>
      </template>

      <template #roles-cell="{ row }">
        <div class="flex flex-wrap gap-1">
          <UBadge
              v-for="role in row.original.roles"
              :key="role"
              :color="getRoleColor(role)"
              variant="soft"
          >
            {{ role }}
          </UBadge>
        </div>
      </template>

      <template #actions-cell="{ row }">
        <div class="flex justify-end">
          <UDropdownMenu :items="getUserActions(row.original)">
            <UButton
                icon="i-lucide-ellipsis"
                color="neutral"
                variant="ghost"
                square
            />
          </UDropdownMenu>
        </div>
      </template>
    </UTable>
  </UCard>
</template>