<script setup lang="ts">
type UserRole = 'super-admin' | 'admin' | 'editor' | 'user'

type UserProfile = {
  id: string | number
  name: string
  email: string
  role: UserRole
  createdAt: string
  updatedAt: string
}

defineProps<{
  user: UserProfile
}>()

const getRoleLabel = (role: UserRole) => {
  switch (role) {
    case 'super-admin':
      return 'Super admin'
    case 'admin':
      return 'Admin'
    case 'editor':
      return 'Editor'
    case 'user':
      return 'User'
  }
}

const getRoleColor = (role: UserRole) => {
  switch (role) {
    case 'super-admin':
      return 'error'
    case 'admin':
      return 'primary'
    case 'editor':
      return 'info'
    case 'user':
      return 'neutral'
  }
}
</script>

<template>
  <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
    <div class="space-y-6">
      <UCard>
        <template #header>
          <div class="flex items-center gap-4">
            <UAvatar
                :alt="user.name"
                size="3xl"
            />

            <div class="min-w-0">
              <h2 class="text-xl font-semibold truncate">
                {{ user.name }}
              </h2>

              <p class="text-sm text-muted truncate">
                {{ user.email }}
              </p>

              <div class="mt-3">
                <UBadge
                    :color="getRoleColor(user.role)"
                    variant="soft"
                >
                  {{ getRoleLabel(user.role) }}
                </UBadge>
              </div>
            </div>
          </div>
        </template>

        <div class="grid gap-4 sm:grid-cols-2">
          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon
                  name="i-lucide-hash"
                  class="size-4"
              />

              User ID
            </div>

            <p class="mt-2 font-medium">
              #{{ user.id }}
            </p>
          </div>

          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon
                  name="i-lucide-mail"
                  class="size-4"
              />

              Email
            </div>

            <p class="mt-2 font-medium truncate">
              {{ user.email }}
            </p>
          </div>

          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon
                  name="i-lucide-calendar-plus"
                  class="size-4"
              />

              Created
            </div>

            <p class="mt-2 font-medium">
              {{ user.createdAt }}
            </p>
          </div>

          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon
                  name="i-lucide-calendar-clock"
                  class="size-4"
              />

              Updated
            </div>

            <p class="mt-2 font-medium">
              {{ user.updatedAt }}
            </p>
          </div>
        </div>
      </UCard>

      <UCard>
        <template #header>
          <div>
            <h2 class="text-base font-semibold">
              Activity
            </h2>

            <p class="mt-1 text-sm text-muted">
              Recent user activity preview.
            </p>
          </div>
        </template>

        <UAlert
            icon="i-lucide-info"
            color="neutral"
            variant="soft"
            title="No activity connected yet"
            description="Later you can show authored posts, login history or admin actions here."
        />
      </UCard>
    </div>

    <aside class="space-y-6 xl:sticky xl:top-6 xl:self-start">
      <UCard>
        <template #header>
          <h2 class="text-base font-semibold">
            Account actions
          </h2>
        </template>

        <div class="flex flex-col gap-3">
          <UButton
              :to="`/users/${user.id}/edit`"
              icon="i-lucide-pencil"
              block
          >
            Edit user
          </UButton>

          <UButton
              color="error"
              variant="soft"
              icon="i-lucide-trash"
              block
          >
            Delete user
          </UButton>
        </div>
      </UCard>

      <UCard>
        <template #header>
          <h2 class="text-base font-semibold">
            Access
          </h2>
        </template>

        <div class="space-y-3">
          <div class="flex items-center justify-between gap-4">
            <span class="text-sm text-muted">
              Current role
            </span>

            <UBadge
                :color="getRoleColor(user.role)"
                variant="soft"
            >
              {{ getRoleLabel(user.role) }}
            </UBadge>
          </div>

          <USeparator />

          <p class="text-sm text-muted">
            Permissions are resolved by the backend through the assigned role.
          </p>
        </div>
      </UCard>
    </aside>
  </div>
</template>