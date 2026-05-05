<script setup lang="ts">

import LoginForm from "~/components/auth/form/LoginForm.vue";

definePageMeta({
  layout: 'auth',
  middleware: 'guest'
})
</script>

<template>
  <UPage>
    <UPageBody>
      <UCard>
        <template #header>
          <div class="text-center">
            <div class="mx-auto mb-4 size-12 rounded-2xl bg-primary/10 flex items-center justify-center ring-1 ring-primary/20">
              <UIcon
                  name="i-lucide-newspaper"
                  class="size-6 text-primary"
              />
            </div>

            <h1 class="text-2xl font-bold tracking-tight">
              Brief Journal
            </h1>

            <p class="mt-2 text-sm text-muted">
              Sign in to your admin panel
            </p>
          </div>
        </template>

        <LoginForm />
      </UCard>
    </UPageBody>
  </UPage>
</template>