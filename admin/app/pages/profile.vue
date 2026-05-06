<script setup lang="ts">
import ProfileCard from "~/components/profile/ProfileCard.vue";
import ProfilePasswordForm from "~/components/profile/form/ProfilePasswordForm.vue";
</script>

<template>
  <UPage>
    <UPageHeader
      title="Profile"
      description="Manage your account information and password."
    />

    <UPageBody>
      <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
        <ProfileCard />

        <ProfilePasswordForm />
      </div>
    </UPageBody>
  </UPage>
</template>
