<script setup lang="ts">
import type { FormSubmitEvent } from "#ui/types";
import {
  type UpdateProfileCredentials,
  updateProfileSchema,
} from "~/resources/profile";
import type { FetchError } from "ofetch";

const { user: profile } = useUserSession();

const profileStore = useProfileStore();
const toast = useToast();

const { loading } = storeToRefs(profileStore);

const state = reactive<UpdateProfileCredentials>({
  name: profile.value?.name ?? "",
  email: profile.value?.email ?? "",
});

const handleSubmit = async (
  event: FormSubmitEvent<UpdateProfileCredentials>,
) => {
  try {
    await profileStore.updateProfile(event.data);

    toast.add({
      title: "Profile updated",
      description: "Your profile has been updated successfully.",
      color: "success",
    });
  } catch (error) {
    const fetchError = error as FetchError<{ message?: string }>;

    toast.add({
      title: "Something went wrong",
      description: fetchError.data?.message ?? "Failed to update profile.",
      color: "error",
    });
  }
};
</script>

<template>
  <UForm
    :state="state"
    :schema="updateProfileSchema"
    class="space-y-5"
    @submit="handleSubmit"
  >
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

        <p class="mt-2 font-medium">#{{ profile?.id ?? "unknown" }}</p>
      </div>

      <div class="rounded-xl border border-default bg-elevated/30 p-4">
        <div class="flex items-center gap-2 text-sm text-muted">
          <UIcon name="i-lucide-calendar-plus" class="size-4" />

          Created
        </div>

        <p class="mt-2 font-medium">
          {{ formatDate(profile?.created_at) }}
        </p>
      </div>
    </div>

    <div class="flex justify-end">
      <UButton type="submit" icon="i-lucide-save" :loading="loading">
        Save changes
      </UButton>
    </div>
  </UForm>
</template>
