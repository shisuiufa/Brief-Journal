<script setup lang="ts">
import type { FormSubmitEvent } from "#ui/types";
import { type Role, Roles } from "~/resources/role";
import {
  type CreateUserCredentials,
  createUserSchema,
  type UpdateUserCredentials,
  updateUserSchema,
  type UserResource,
} from "~/resources/user";
import type { FetchError } from "ofetch";

type UserFormState = CreateUserCredentials | UpdateUserCredentials;

const props = withDefaults(
  defineProps<{
    mode?: "create" | "edit";
    user?: UserResource;
  }>(),
  {
    mode: "create",
    user: undefined,
  },
);

const toast = useToast();
const userStore = useUserStore();

const { loading } = storeToRefs(userStore);
const { hasRole } = useUserAccess();
const { user: userSession } = useUserSession();

const isEditMode = computed(() => props.mode === "edit");
const formSchema = computed(() =>
  isEditMode.value ? updateUserSchema : createUserSchema,
);

const state = reactive<UserFormState>({
  name: props.user?.name ?? "",
  email: props.user?.email ?? "",
  password: "",
  password_confirmation: "",
  role: props.user?.roles[0] ?? Roles.Editor,
});

const roleItems = computed<
  Array<{
    label: string;
    value: Role;
    icon: string;
  }>
>(() => {
  if (hasRole(Roles.Admin)) {
    return [
      {
        label: "Editor",
        value: Roles.Editor,
        icon: "i-lucide-pen-line",
      },
    ];
  }

  if (hasRole(Roles.SuperAdmin)) {
    return [
      {
        label: "Editor",
        value: Roles.Editor,
        icon: "i-lucide-pen-line",
      },
      {
        label: "Admin",
        value: Roles.Admin,
        icon: "i-lucide-shield-check",
      },
    ];
  }

  return [];
});

const submitLabel = computed(() => {
  return isEditMode.value ? "Update user" : "Create user";
});

const submitIcon = computed(() => {
  return isEditMode.value ? "i-lucide-save" : "i-lucide-user-plus";
});

const canChangeRole = computed(() => {
  const isCurrentUser = props.user?.id === userSession.value?.id;

  return (
    !isCurrentUser &&
    (hasRole(Roles.SuperAdmin) || (hasRole(Roles.Admin) && !isEditMode.value))
  );
});

const handleSubmit = async (event: FormSubmitEvent<UserFormState>) => {
  try {
    if (isEditMode.value) {
      const user = props.user!;

      await userStore.update(user.id, event.data as UpdateUserCredentials);
    } else {
      await userStore.create(event.data as CreateUserCredentials);
    }

    toast.add({
      title: isEditMode.value ? "User updated" : "User created",
      description: isEditMode.value
        ? "The user has been updated successfully."
        : "The user has been created successfully.",
      color: "success",
    });

    await navigateTo("/users");
  } catch (error) {
    const fetchError = error as FetchError<{ message?: string }>;

    const fallbackMessage = isEditMode.value
      ? "Failed to update user."
      : "Failed to create user.";

    toast.add({
      title: "Something went wrong",
      description: fetchError.data?.message ?? fallbackMessage,
      color: "error",
    });
  }
};
</script>

<template>
  <UForm
    :schema="formSchema"
    :state="state"
    :class="canChangeRole ? 'xl:grid-cols-[minmax(0,1fr)_360px]' : ''"
    class="grid gap-6"
    @submit="handleSubmit"
  >
    <div class="space-y-6">
      <UCard>
        <template #header>
          <div>
            <h2 class="text-base font-semibold">User information</h2>

            <p class="mt-1 text-sm text-muted">Basic account details.</p>
          </div>
        </template>

        <div class="space-y-5">
          <UFormField label="Name" name="name" required>
            <UInput
              v-model="state.name"
              size="lg"
              icon="i-lucide-user"
              placeholder="John Doe"
              class="w-full"
            />
          </UFormField>

          <UFormField label="Email" name="email" required>
            <UInput
              v-model="state.email"
              type="email"
              size="lg"
              icon="i-lucide-mail"
              placeholder="john@example.com"
              class="w-full"
            />
          </UFormField>

          <USeparator />

          <div>
            <h3 class="text-sm font-medium">Password</h3>

            <p class="mt-1 text-sm text-muted">
              {{
                isEditMode
                  ? "Leave blank if you do not want to change the password."
                  : "Set password for the new user."
              }}
            </p>
          </div>

          <div class="grid gap-5 md:grid-cols-2">
            <UFormField label="Password" name="password" required>
              <UInput
                v-model="state.password"
                type="password"
                icon="i-lucide-lock"
                placeholder="Minimum 8 characters"
                class="w-full"
              />
            </UFormField>

            <UFormField
              label="Confirm password"
              name="password_confirmation"
              required
            >
              <UInput
                v-model="state.password_confirmation"
                type="password"
                icon="i-lucide-lock-keyhole"
                placeholder="Repeat password"
                class="w-full"
              />
            </UFormField>
          </div>
        </div>
      </UCard>
    </div>

    <aside class="space-y-6 xl:sticky xl:top-6 xl:self-start">
      <UCard v-if="canChangeRole">
        <template #header>
          <div>
            <h2 class="text-base font-semibold">Access</h2>

            <p class="mt-1 text-sm text-muted">
              Choose the role for this user.
            </p>
          </div>
        </template>

        <div class="space-y-5">
          <UFormField label="Role" name="role" required>
            <USelect v-model="state.role" :items="roleItems" class="w-full" />
          </UFormField>

          <UAlert
            icon="i-lucide-info"
            color="neutral"
            variant="soft"
            title="Role permissions"
            description="Permissions are controlled by the backend. This form only assigns the selected role."
          />
        </div>
      </UCard>

      <UCard>
        <div class="flex flex-col gap-3">
          <UButton type="submit" :icon="submitIcon" block :loading="loading">
            {{ submitLabel }}
          </UButton>

          <UButton to="/users" color="neutral" variant="soft" block>
            Cancel
          </UButton>
        </div>
      </UCard>
    </aside>
  </UForm>
</template>
