<script setup lang="ts">

import AppSidebar from "~/components/AppSidebar.vue";
import AppHeader from "~/components/AppHeader.vue";

const openSidebar = ref(true);
</script>

<template>
  <div class="flex flex-1">
    <AppSidebar v-model:open="openSidebar" />

    <div class="flex-1 flex flex-col">
      <AppHeader @toggle="openSidebar = !openSidebar"/>
      <div class="flex-1 p-4">
        <slot />
      </div>
    </div>
  </div>
</template>
