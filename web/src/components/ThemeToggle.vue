<script setup lang="ts">
import { useTheme } from '@/composables/useTheme';
import { MoonIcon, SunIcon } from '@heroicons/vue/24/outline';
import { onMounted, ref } from 'vue';

const { toggleTheme, mode } = useTheme();

const mounted = ref(false);

onMounted(() => {
  mounted.value = true;
});
</script>

<template>
  <button
    type="button"
    @click="toggleTheme"
    class="bg-button-tag border border-default shadow-soft text-foreground inline-flex cursor-pointer items-center justify-center rounded-full p-2 transition hover:scale-[1.03] hover:bg-accent-soft hover:text-accent"
    :aria-label="mode === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
  >
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 scale-75 rotate-[-12deg]"
      enter-to-class="opacity-100 scale-100 rotate-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 scale-100 rotate-0"
      leave-to-class="opacity-0 scale-75 rotate-[12deg]"
      mode="out-in"
    >
      <SunIcon v-if="mounted && mode !== 'light'" key="sun" class="size-5" />

      <MoonIcon v-else-if="mounted" key="moon" class="size-5" />

      <span v-else key="placeholder" class="size-5" />
    </Transition>
  </button>
</template>
