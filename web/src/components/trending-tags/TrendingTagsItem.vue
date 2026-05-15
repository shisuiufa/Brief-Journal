<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps<{
  label: string;
  slug: string;
  count: number;
}>();

const route = useRoute();

const isActive = computed(() => route.query.tag === props.slug);

const target = computed(() => ({
  name: 'home',
  query: {
    ...route.query,
    tag: isActive.value ? undefined : props.slug,
    page: undefined,
  },
}));
</script>

<template>
  <RouterLink
    :to="target"
    class="border border-default shadow-soft group inline-flex cursor-pointer items-center gap-3 rounded-2xl px-4 py-3 text-left transition hover:-translate-y-0.5"
    :class="
      isActive
        ? 'bg-accent text-accent-foreground'
        : 'bg-button-tag text-foreground hover:bg-accent-soft hover:text-accent'
    "
  >
    <span class="text-sm font-semibold">{{ label }}</span>
    <span
      class="border border-default rounded-full px-2 py-0.5 text-[0.68rem] font-semibold"
      :class="isActive ? 'bg-background/20 text-accent-foreground' : 'bg-background text-muted'"
    >
      {{ count }}
    </span>
  </RouterLink>
</template>
