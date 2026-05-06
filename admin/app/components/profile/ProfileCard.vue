<script setup lang="ts">
import { getRoleColor } from "~/utils/role";
import ProfileForm from "~/components/profile/form/ProfileForm.vue";

const { user: profile } = useUserSession();
</script>

<template>
  <UCard>
    <template #header>
      <div v-if="profile" class="flex items-center gap-4">
        <UAvatar :alt="profile.name" size="3xl" />

        <div class="min-w-0">
          <h2 class="text-xl font-semibold truncate">
            {{ profile.name }}
          </h2>

          <p class="text-sm text-muted truncate">
            {{ profile.email }}
          </p>

          <UBadge
            v-for="(role, idx) in profile.roles"
            :key="idx"
            :color="getRoleColor(role)"
            variant="soft"
            class="mt-3"
          >
            {{ role }}
          </UBadge>
        </div>
      </div>
    </template>

    <ProfileForm />
  </UCard>
</template>
