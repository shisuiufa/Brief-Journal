<script setup lang="ts">
import { useProfileStore } from '~/stores/useProfileStore'
import {type LoginCredentials, loginSchema} from "~/resources/user";
import type { FormSubmitEvent } from '#ui/types'
import {FetchError} from "ofetch";

const notify = useNotify()
const profileStore = useProfileStore()

const { loading } = storeToRefs(profileStore);

const state = reactive<LoginCredentials>({
  email: '',
  password: ''
})
const handleSubmit = async (event: FormSubmitEvent<LoginCredentials>) => {
  try {
    await profileStore.login(event.data)

    notify.success({
      title: 'Login successful',
      description: 'Welcome back to the admin panel.'
    })

    await navigateTo('/')
  } catch (error) {
    const fetchError = error as FetchError<{ message?: string }>

    notify.error({
      title: 'Login failed.',
      description: fetchError.data?.message ?? 'Failed to login.'
    })
  }
}
</script>

<template>
  <UForm
      :schema="loginSchema"
      :state="state"
      class="space-y-5"
      @submit="handleSubmit"
  >
    <UFormField
        label="Email"
        name="email"
        required
    >
      <UInput
          v-model="state.email"
          type="email"
          icon="i-lucide-mail"
          placeholder="admin@example.com"
          autocomplete="email"
          class="w-full"
      />
    </UFormField>

    <UFormField
        label="Password"
        name="password"
        required
    >
      <UInput
          v-model="state.password"
          type="password"
          icon="i-lucide-lock"
          placeholder="Any password"
          autocomplete="current-password"
          class="w-full"
      />
    </UFormField>

    <UButton
        type="submit"
        icon="i-lucide-log-in"
        :loading="loading"
        block
    >
      Sign in
    </UButton>
  </UForm>
</template>
