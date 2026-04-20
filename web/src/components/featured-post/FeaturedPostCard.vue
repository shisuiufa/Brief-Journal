<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue'
import PostAuthor from '@/components/post/PostAuthor.vue'
</script>

<template>
  <UiCard class="p-5 xl:p-8 cursor-pointer">
    <div class="xl:flex gap-4 md:gap-8">
      <div class="w-full xl:w-2xs mb-3 xl:mt-0 h-auto rounded-2xl overflow-hidden">
        <img
          src="https://i.pinimg.com/originals/4c/0b/e5/4c0be594d9e76702f5a61c08176bb600.jpg"
          alt="post"
          class="w-full h-full object-cover object-center"
        />
      </div>

      <div class="h-auto flex flex-col justify-between">
        <div>
          <p class="font-semibold text-sm mb-2 text-muted uppercase tracking-[0.12em]">category</p>

          <h1 class="font-bold text-3xl mb-3 text-foreground">
            The Future of Generative UI: Beyond Component Libraries
          </h1>

          <p class="text-base mb-3 text-muted">
            Exploring how algorithmic design systems are shifting from static assets to dynamic,
            context-aware interfaces…
          </p>
        </div>

        <PostAuthor />
      </div>
    </div>
  </UiCard>
</template>
