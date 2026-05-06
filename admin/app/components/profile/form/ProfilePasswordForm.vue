<script setup lang="ts">
import type { FormSubmitEvent } from "#ui/types";
import {
  type UpdateProfilePasswordCredentials,
  updateProfilePasswordSchema,
} from "~/resources/profile";
import type { FetchError } from "ofetch";

const profileStore = useProfileStore();

const { loading } = storeToRefs(profileStore);

const toast = useToast();

const state = reactive<UpdateProfilePasswordCredentials>({
  currentPassword: "",
  password: "",
  passwordConfirmation: "",
});

const handleSubmit = async (
  event: FormSubmitEvent<UpdateProfilePasswordCredentials>,
) => {
  try {
    await profileStore.updatePassword(event.data);

    state.currentPassword = "";
    state.password = "";
    state.passwordConfirmation = "";

    toast.add({
      title: "Password updated",
      description: "Your password has been changed successfully.",
      color: "success",
    });
  } catch (error) {
    const fetchError = error as FetchError<{ message?: string }>;

    toast.add({
      title: "Something went wrong",
      description: fetchError.data?.message ?? "Failed to update password.",
      color: "error",
    });
  }
};
</script>

<template>
  <aside class="space-y-6 xl:sticky xl:top-6 xl:self-start">
    <UCard>
      <template #header>
        <div>
          <h2 class="text-base font-semibold">Change password</h2>

          <p class="mt-1 text-sm text-muted">Update your account password.</p>
        </div>
      </template>

      <UForm
        :schema="updateProfilePasswordSchema"
        :state="state"
        class="space-y-5"
        @submit="handleSubmit"
      >
        <UFormField label="Current password" name="currentPassword" required>
          <UInput
            v-model="state.currentPassword"
            type="password"
            icon="i-lucide-lock"
            placeholder="Current password"
            class="w-full"
          />
        </UFormField>

        <UFormField label="New password" name="password" required>
          <UInput
            v-model="state.password"
            type="password"
            icon="i-lucide-lock-keyhole"
            placeholder="Minimum 8 characters"
            class="w-full"
          />
        </UFormField>

        <UFormField
          label="Confirm password"
          name="passwordConfirmation"
          required
        >
          <UInput
            v-model="state.passwordConfirmation"
            type="password"
            icon="i-lucide-shield-check"
            placeholder="Repeat new password"
            class="w-full"
          />
        </UFormField>

        <UButton type="submit" icon="i-lucide-save" :loading="loading" block>
          Update password
        </UButton>
      </UForm>
    </UCard>

    <UAlert
      icon="i-lucide-info"
      color="neutral"
      variant="soft"
      title="Security note"
      description="After changing your password, the backend may invalidate other active sessions."
    />
  </aside>
</template>
