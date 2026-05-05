<script setup lang="ts">
type UserRole = 'super-admin' | 'admin' | 'editor' | 'user'

type UserFormMode = 'create' | 'edit'

type UserFormInitialState = {
  name: string
  email: string
  role: UserRole
}

type UserFormState = {
  name: string
  email: string
  password: string
  passwordConfirmation: string
  role: UserRole
}

const props = withDefaults(defineProps<{
  mode?: UserFormMode
  userId?: string | number
  initialState?: UserFormInitialState
}>(), {
  mode: 'create',
  userId: undefined,
  initialState: undefined,
})

const toast = useToast()

const isEditMode = computed(() => props.mode === 'edit')

const state = reactive<UserFormState>({
  name: props.initialState?.name ?? '',
  email: props.initialState?.email ?? '',
  password: '',
  passwordConfirmation: '',
  role: props.initialState?.role ?? 'user',
})

const isSubmitting = ref(false)

const roleItems = [
  {
    label: 'Super admin',
    value: 'super-admin',
    icon: 'i-lucide-shield-alert',
  },
  {
    label: 'Admin',
    value: 'admin',
    icon: 'i-lucide-shield-check',
  },
  {
    label: 'Editor',
    value: 'editor',
    icon: 'i-lucide-pen-line',
  },
  {
    label: 'User',
    value: 'user',
    icon: 'i-lucide-user',
  },
]

const submitLabel = computed(() => {
  return isEditMode.value ? 'Update user' : 'Create user'
})

const submitIcon = computed(() => {
  return isEditMode.value ? 'i-lucide-save' : 'i-lucide-user-plus'
})

const handleSubmit = async () => {
  isSubmitting.value = true

  try {
    const payload: Record<string, string> = {
      name: state.name,
      email: state.email,
      role: state.role,
    }

    if (state.password) {
      payload.password = state.password
      payload.password_confirmation = state.passwordConfirmation
    }

    if (isEditMode.value) {
      // TODO: заменить на свой API клиент
      // await $fetch(`/api/admin/users/${props.userId}`, {
      //   method: 'PUT',
      //   body: payload,
      // })

      console.log('update user', props.userId, payload)
    } else {
      // TODO: заменить на свой API клиент
      // await $fetch('/api/admin/users', {
      //   method: 'POST',
      //   body: payload,
      // })

      console.log('create user', payload)
    }

    toast.add({
      title: isEditMode.value ? 'User updated' : 'User created',
      description: isEditMode.value
          ? 'The user has been updated successfully.'
          : 'The user has been created successfully.',
      color: 'success',
    })

    await navigateTo('/users')
  } catch {
    toast.add({
      title: 'Something went wrong',
      description: isEditMode.value
          ? 'Failed to update user.'
          : 'Failed to create user.',
      color: 'error',
    })
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <UForm
      :state="state"
      class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
      @submit="handleSubmit"
  >
    <div class="space-y-6">
      <UCard>
        <template #header>
          <div>
            <h2 class="text-base font-semibold">
              User information
            </h2>

            <p class="mt-1 text-sm text-muted">
              Basic account details.
            </p>
          </div>
        </template>

        <div class="space-y-5">
          <UFormField
              label="Name"
              name="name"
              required
          >
            <UInput
                v-model="state.name"
                size="lg"
                icon="i-lucide-user"
                placeholder="John Doe"
                class="w-full"
            />
          </UFormField>

          <UFormField
              label="Email"
              name="email"
              required
          >
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
            <h3 class="text-sm font-medium">
              Password
            </h3>

            <p class="mt-1 text-sm text-muted">
              {{ isEditMode ? 'Leave password fields empty to keep current password.' : 'Set password for the new user.' }}
            </p>
          </div>

          <div class="grid gap-5 md:grid-cols-2">
            <UFormField
                label="Password"
                name="password"
                :required="!isEditMode"
            >
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
                name="passwordConfirmation"
                :required="!isEditMode"
            >
              <UInput
                  v-model="state.passwordConfirmation"
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
      <UCard>
        <template #header>
          <div>
            <h2 class="text-base font-semibold">
              Access
            </h2>

            <p class="mt-1 text-sm text-muted">
              Choose the role for this user.
            </p>
          </div>
        </template>

        <div class="space-y-5">
          <UFormField
              label="Role"
              name="role"
              required
          >
            <USelect
                v-model="state.role"
                :items="roleItems"
                class="w-full"
            />
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
          <UButton
              type="submit"
              :icon="submitIcon"
              :loading="isSubmitting"
              block
          >
            {{ submitLabel }}
          </UButton>

          <UButton
              to="/users"
              color="neutral"
              variant="soft"
              block
          >
            Cancel
          </UButton>
        </div>
      </UCard>
    </aside>
  </UForm>
</template>