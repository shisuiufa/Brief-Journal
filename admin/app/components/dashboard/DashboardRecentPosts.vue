<script setup lang="ts">
import DashboardRecentPostList from "~/components/dashboard/DashboardRecentPostList.vue";

defineProps<{
  loading?: boolean
}>()

</script>

<template>
  <UCard>
    <template #header>
      <div class="flex items-center justify-between gap-4">
        <div>
          <h2 class="font-semibold">
            Recent posts
          </h2>

          <p class="text-sm text-muted">
            Latest content activity.
          </p>
        </div>

        <UButton
            to="/posts"
            color="neutral"
            variant="ghost"
            trailing-icon="i-lucide-arrow-right"
        >
          View all
        </UButton>
      </div>
    </template>

    <DashboardRecentPostList :loading="loading" />
  </UCard>
</template>