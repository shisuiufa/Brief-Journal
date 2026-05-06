<script setup lang="ts">
const toast = useToast();

const state = reactive({
  currentPassword: "",
  password: "",
  passwordConfirmation: "",
});

const isSubmitting = ref(false);

const handleSubmit = async () => {
  isSubmitting.value = true;

  try {
    const payload = {
      current_password: state.currentPassword,
      password: state.password,
      password_confirmation: state.passwordConfirmation,
    };

    // TODO: заменить на свой API клиент
    // await $fetch('/api/admin/profile/password', {
    //   method: 'PUT',
    //   body: payload,
    // })

    console.log("update password", payload);

    state.currentPassword = "";
    state.password = "";
    state.passwordConfirmation = "";

    toast.add({
      title: "Password updated",
      description: "Your password has been changed successfully.",
      color: "success",
    });
  } catch {
    toast.add({
      title: "Something went wrong",
      description: "Failed to update password.",
      color: "error",
    });
  } finally {
    isSubmitting.value = false;
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

      <UForm :state="state" class="space-y-5" @submit="handleSubmit">
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

        <UButton
          type="submit"
          icon="i-lucide-save"
          :loading="isSubmitting"
          block
        >
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
