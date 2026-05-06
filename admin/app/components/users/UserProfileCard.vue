<script setup lang="ts">
import type { UserResource } from "~/resources/user";

const props = defineProps<{
  user: UserResource;
}>();

const toast = useToast();
const userStore = useUserStore();

const { loading } = storeToRefs(userStore);
const { canEditUser, canDeleteUser } = useUserPolicy();

const handleDelete = async () => {
  try {
    await userStore.destroy(props.user.id);
    toast.add({
      title: "User deleted",
      description: "The account has been removed.",
      color: "success",
    });
    navigateTo("/users");
  } catch {
    toast.add({
      title: "Failed to delete user",
      description: "Please try again.",
      color: "error",
    });
  }
};
</script>

<template>
  <div
    :class="{
      'grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]':
        canEditUser(user) || canDeleteUser(user),
    }"
  >
    <div class="space-y-6">
      <UCard>
        <template #header>
          <div class="flex items-center gap-4">
            <UAvatar :alt="user.name" size="3xl" />

            <div class="min-w-0">
              <h2 class="text-xl font-semibold truncate">
                {{ user.name }}
              </h2>

              <p class="text-sm text-muted truncate">
                {{ user.email }}
              </p>

              <div class="mt-3 flex items-center gap-2">
                <UBadge
                  v-for="(role, idx) in user.roles"
                  :key="idx"
                  :color="getRoleColor(role)"
                  variant="soft"
                >
                  {{ user.roles[0] }}
                </UBadge>
              </div>
            </div>
          </div>
        </template>

        <div class="grid gap-4 sm:grid-cols-2">
          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon name="i-lucide-hash" class="size-4" />

              User ID
            </div>

            <p class="mt-2 font-medium">#{{ user.id }}</p>
          </div>

          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon name="i-lucide-mail" class="size-4" />

              Email
            </div>

            <p class="mt-2 font-medium truncate">
              {{ user.email }}
            </p>
          </div>

          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon name="i-lucide-calendar-plus" class="size-4" />

              Created
            </div>

            <p class="mt-2 font-medium">
              {{ formatDate(user.created_at) }}
            </p>
          </div>

          <div class="rounded-xl border border-default bg-elevated/30 p-4">
            <div class="flex items-center gap-2 text-sm text-muted">
              <UIcon name="i-lucide-calendar-clock" class="size-4" />

              Updated
            </div>

            <p class="mt-2 font-medium">
              {{ formatDate(user.updated_at) }}
            </p>
          </div>
        </div>
      </UCard>

      <UCard>
        <template #header>
          <div>
            <h2 class="text-base font-semibold">Activity</h2>

            <p class="mt-1 text-sm text-muted">Recent user activity preview.</p>
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

    <aside
      v-if="canEditUser(user) || canDeleteUser(user)"
      class="space-y-6 xl:sticky xl:top-6 xl:self-start"
    >
      <UCard>
        <template #header>
          <h2 class="text-base font-semibold">Account actions</h2>
        </template>

        <div class="flex flex-col gap-3">
          <UButton
            v-if="canEditUser(user)"
            :to="`/users/${user.id}/edit`"
            icon="i-lucide-pencil"
            block
          >
            Edit user
          </UButton>

          <UButton
            v-if="canDeleteUser(user)"
            color="error"
            variant="soft"
            icon="i-lucide-trash"
            :loading="loading"
            block
            @click="handleDelete"
          >
            Delete user
          </UButton>
        </div>
      </UCard>
    </aside>
  </div>
</template>
