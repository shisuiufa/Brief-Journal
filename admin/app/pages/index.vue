<script setup lang="ts">
import DashboardStats from "~/components/dashboard/DashboardStats.vue";
import DashboardRecentPosts from "~/components/dashboard/DashboardRecentPosts.vue";
import DashboardQuickActions from "~/components/dashboard/DashboardQuickActions.vue";

const postStore = usePostStore();

const { pending } = await useLazyAsyncData("posts", () =>
  postStore.fetchPosts(),
);
</script>

<template>
  <UPage>
    <UPageHeader
      title="Dashboard"
      description="Overview of your blog admin panel."
    />
    <UPageBody>
      <DashboardStats :loading="pending" />

      <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <DashboardRecentPosts class="lg:col-span-2" :loading="pending" />
        <DashboardQuickActions />
      </div>
    </UPageBody>
  </UPage>
</template>
