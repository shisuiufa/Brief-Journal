<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import PostAuthor from '@/components/post/PostAuthor.vue'
</script>

<template>
  <UiCard class="p-5 xl:p-8 cursor-pointer">
    <div class="w-full xl:h-80 rounded-2xl overflow-hidden mb-3">
      <img
        src="https://i.pinimg.com/originals/4c/0b/e5/4c0be594d9e76702f5a61c08176bb600.jpg"
        alt="post"
        class="w-full h-full object-cover"
      />
    </div>

    <p class="font-semibold text-xs mb-2 text-muted uppercase tracking-[0.12em]">category</p>

    <h1 class="font-bold text-xl mb-1 text-foreground leading-normal">
      The Future of Generative UI: Beyond Component Libraries
    </h1>

    <p class="text-sm mb-3 text-muted leading-normal">
      Exploring how algorithmic design systems are <br />
      shifting from static assets to dynamic, context-aware interfaces…
    </p>

    <PostAuthor />
  </UiCard>
</template>
