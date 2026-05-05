<script setup lang="ts">
import type { DropdownMenuItem, TableColumn } from '@nuxt/ui'

type UserRole = 'Super admin' | 'Admin' | 'Editor' | 'User'

type User = {
  id: number
  name: string
  email: string
  role: UserRole
  createdAt: string
}

const users = ref<User[]>([
  {
    id: 1,
    name: 'Admin',
    email: 'admin@example.com',
    role: 'Super admin',
    createdAt: 'Apr 30, 2026',
  },
  {
    id: 2,
    name: 'Editor',
    email: 'editor@example.com',
    role: 'Editor',
    createdAt: 'Apr 29, 2026',
  },
  {
    id: 3,
    name: 'John Doe',
    email: 'john@example.com',
    role: 'User',
    createdAt: 'Apr 26, 2026',
  },
])

const columns: TableColumn<User>[] = [
  {
    accessorKey: 'name',
    header: 'User',
  },
  {
    accessorKey: 'role',
    header: 'Role',
  },
  {
    accessorKey: 'createdAt',
    header: 'Created',
  },
  {
    id: 'actions',
  },
]

const getRoleColor = (role: UserRole) => {
  switch (role) {
    case 'Super admin':
      return 'error'
    case 'Admin':
      return 'primary'
    case 'Editor':
      return 'info'
    case 'User':
      return 'neutral'
  }
}

const getUserActions = (user: User): DropdownMenuItem[][] => [
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

      <template #role-cell="{ row }">
        <UBadge
            :color="getRoleColor(row.original.role)"
            variant="soft"
        >
          {{ row.original.role }}
        </UBadge>
      </template>

      <template #createdAt-cell="{ row }">
        <span class="text-muted">
          {{ row.original.createdAt }}
        </span>
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