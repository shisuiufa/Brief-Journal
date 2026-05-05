<script setup lang="ts">
const props = defineProps<{
  loading?: boolean
}>()

const postStore = usePostStore()
const { list } = storeToRefs(postStore)

const postsCount = computed(() => list.value?.length || 0)

const publishedCount = computed(() => {
  return list.value?.filter((post) => post.status === 'published').length || 0
})

const draftCount = computed(() => {
  return list.value?.filter((post) => post.status === 'draft').length || 0
})

const stats = computed(() => [
  {
    title: 'Posts',
    value: String(postsCount.value),
    description: 'Total blog posts',
    icon: 'i-lucide-newspaper',
    to: '/posts',
  },
  {
    title: 'Published',
    value: String(publishedCount.value),
    description: 'Visible on the website',
    icon: 'i-lucide-circle-check',
    to: '/posts?status=published',
  },
  {
    title: 'Drafts',
    value: String(draftCount.value),
    description: 'Waiting for publication',
    icon: 'i-lucide-file-pen-line',
    to: '/posts?status=draft',
  },
])

const skeletonCards = 3
</script>

<template>
  <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <UCard
        v-if="props.loading"
        v-for="index in skeletonCards"
        :key="`stat-skeleton-${index}`"
    >
      <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
          <USkeleton class="h-4 w-20 rounded-md" />
          <USkeleton class="mt-3 h-9 w-16 rounded-md" />
          <USkeleton class="mt-2 h-4 w-36 rounded-md" />
        </div>

        <USkeleton class="size-10 rounded-xl" />
      </div>
    </UCard>

    <UCard
        v-else
        v-for="stat in stats"
        :key="stat.title"
        :to="stat.to"
        class="transition hover:-translate-y-0.5 hover:shadow-md"
    >
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-sm text-muted">
            {{ stat.title }}
          </p>

          <p class="mt-2 text-3xl font-bold tracking-tight">
            {{ stat.value }}
          </p>

          <p class="mt-1 text-sm text-muted">
            {{ stat.description }}
          </p>
        </div>

        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
          <UIcon
              :name="stat.icon"
              class="size-5 text-primary"
          />
        </div>
      </div>
    </UCard>
  </div>
</template>