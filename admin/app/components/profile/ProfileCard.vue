<script setup lang="ts">
type ProfileRole = "super-admin" | "admin" | "editor" | "user";

type Profile = {
  id: number;
  name: string;
  email: string;
  role: ProfileRole;
  createdAt: string;
};

const props = defineProps<{
  profile: Profile;
}>();

const toast = useToast();

const state = reactive({
  name: props.profile.name,
  email: props.profile.email,
});

const isSubmitting = ref(false);

const getRoleLabel = (role: ProfileRole) => {
  switch (role) {
    case "super-admin":
      return "Super admin";
    case "admin":
      return "Admin";
    case "editor":
      return "Editor";
    case "user":
      return "User";
  }
};

const getRoleColor = (role: ProfileRole) => {
  switch (role) {
    case "super-admin":
      return "error";
    case "admin":
      return "primary";
    case "editor":
      return "info";
    case "user":
      return "neutral";
  }
};

const handleSubmit = async () => {
  isSubmitting.value = true;

  try {
    const payload = {
      name: state.name,
      email: state.email,
    };

    // TODO: заменить на свой API клиент
    // await $fetch('/api/admin/profile', {
    //   method: 'PUT',
    //   body: payload,
    // })

    console.log("update profile", payload);

    toast.add({
      title: "Profile updated",
      description: "Your profile has been updated successfully.",
      color: "success",
    });
  } catch {
    toast.add({
      title: "Something went wrong",
      description: "Failed to update profile.",
      color: "error",
    });
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <UCard>
    <template #header>
      <div class="flex items-center gap-4">
        <UAvatar :alt="profile.name" size="3xl" />

        <div class="min-w-0">
          <h2 class="text-xl font-semibold truncate">
            {{ profile.name }}
          </h2>

          <p class="text-sm text-muted truncate">
            {{ profile.email }}
          </p>

          <UBadge
            :color="getRoleColor(profile.role)"
            variant="soft"
            class="mt-3"
          >
            {{ getRoleLabel(profile.role) }}
          </UBadge>
        </div>
      </div>
    </template>

    <UForm :state="state" class="space-y-5" @submit="handleSubmit">
      <div class="grid gap-5 md:grid-cols-2">
        <UFormField label="Name" name="name" required>
          <UInput
            v-model="state.name"
            icon="i-lucide-user"
            placeholder="Your name"
            class="w-full"
          />
        </UFormField>

        <UFormField label="Email" name="email" required>
          <UInput
            v-model="state.email"
            type="email"
            icon="i-lucide-mail"
            placeholder="your@email.com"
            class="w-full"
          />
        </UFormField>
      </div>

      <USeparator />

      <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-default bg-elevated/30 p-4">
          <div class="flex items-center gap-2 text-sm text-muted">
            <UIcon name="i-lucide-hash" class="size-4" />

            Account ID
          </div>

          <p class="mt-2 font-medium">#{{ profile.id }}</p>
        </div>

        <div class="rounded-xl border border-default bg-elevated/30 p-4">
          <div class="flex items-center gap-2 text-sm text-muted">
            <UIcon name="i-lucide-calendar-plus" class="size-4" />

            Created
          </div>

          <p class="mt-2 font-medium">
            {{ profile.createdAt }}
          </p>
        </div>
      </div>

      <div class="flex justify-end">
        <UButton type="submit" icon="i-lucide-save" :loading="isSubmitting">
          Save changes
        </UButton>
      </div>
    </UForm>
  </UCard>
</template>
