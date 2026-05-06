<template>
  <UPage class="h-full" >
    <UPageBody class="flex justify-center items-center w-full h-full" :ui="{base: 'pb-0 mt-0'}">
      <UCard class="w-full max-w-xl">
        <div class="text-center">
          <div class="mx-auto mb-5 size-14 rounded-2xl bg-error/10 flex items-center justify-center ring-1 ring-error/20">
            <UIcon
                name="i-lucide-shield-alert"
                class="size-7 text-error"
            />
          </div>

          <p class="text-sm font-medium text-error">
            403
          </p>

          <h1 class="mt-2 text-2xl font-bold tracking-tight">
            Access denied
          </h1>

          <p class="mt-3 text-sm text-muted">
            You do not have permission to view this section of the admin panel.
          </p>

          <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
            <UButton
                color="neutral"
                variant="soft"
                icon="i-lucide-arrow-left"
                @click="$router.back()"
            >
              Go back
            </UButton>

            <UButton
                to="/"
                icon="i-lucide-layout-dashboard"
            >
              Dashboard
            </UButton>
          </div>
        </div>
      </UCard>
    </UPageBody>
  </UPage>
</template>
