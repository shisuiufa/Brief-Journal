<script setup lang="ts">
import PostArticle from '@/components/post/PostArticle.vue';
import PostSidebar from '@/components/post/PostSidebar.vue';
import PostViewSkeleton from '@/components/post/PostViewSkeleton.vue';
import { usePostStore } from '@/stores/usePostStore';
import { getReadingTime } from '@/utils/readingTime';
import { useHead } from '@unhead/vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { useCallOnce } from '@/composables/useCallOnce.ts';
import NotFoundPanel from '@/components/not-found/NotFoundPanel.vue';
import { useCurrentPostRealtime } from '@/composables/useCurrentPostRealtime.ts';

const route = useRoute();
const postStore = usePostStore();
const callOnce = useCallOnce();

const { currentPost: post, currentPostNotFoundSlug } = storeToRefs(postStore);

const slug = computed(() => String(route.params.slug || ''));
const pageTitle = computed(() => post.value?.title ?? '');
const pageDescription = computed(() => post.value?.excerpt ?? 'Article is unavailable.');
const readTime = computed(() => (post.value ? getReadingTime(post.value.content) : ''));
const isPostReady = computed(() => post.value?.slug === slug.value);

const isPostNotFound = computed(() => currentPostNotFoundSlug.value === slug.value);

useHead(
  computed(() => ({
    title: pageTitle.value,
    meta: [
      {
        name: 'description',
        content: pageDescription.value,
      },
      {
        property: 'og:title',
        content: pageTitle.value,
      },
      {
        property: 'og:description',
        content: pageDescription.value,
      },
      {
        property: 'og:type',
        content: 'article',
      },
      ...(post.value?.image_url
        ? [
            {
              property: 'og:image',
              content: post.value.image_url,
            },
          ]
        : []),
    ],
  })),
);

const loadPost = async () => {
  await postStore.fetchPost(slug.value);
};

callOnce(`post:${slug.value}`, loadPost);

useCurrentPostRealtime(slug);
</script>

<template>
  <NotFoundPanel v-if="isPostNotFound" :requested-path="route.fullPath" />

  <article v-else class="mx-auto w-full max-w-295 py-5 xl:py-8">
    <RouterLink
      to="/"
      class="text-muted hover:text-accent mb-5 inline-flex items-center gap-2 text-sm font-semibold transition"
    >
      <ArrowLeftIcon class="size-4" />
      Back to journal
    </RouterLink>

    <div
      v-if="isPostReady && post"
      class="grid grid-cols-1 gap-4 xl:grid-cols-[minmax(0,1fr)_320px] xl:gap-8"
    >
      <PostArticle :post="post" :read-time="readTime" />
      <PostSidebar :post="post" :read-time="readTime" />
    </div>

    <PostViewSkeleton v-else />
  </article>
</template>
