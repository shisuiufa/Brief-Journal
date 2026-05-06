<script setup lang="ts">
import { useApiPath } from "~/config/entrypoint";

const model = defineModel<File | null>({
  default: null,
});

const props = defineProps<{
  error?: boolean;
  label?: string;
  hint?: string;
  previewAlt?: string;
  previewUrl?: string | null;
}>();

const imagePreview = ref<string | null>(null);
const objectPreviewUrl = ref<string | null>(null);

const resolvedPreviewUrl = computed(() => {
  if (!props.previewUrl) {
    return null;
  }

  if (/^https?:\/\//.test(props.previewUrl)) {
    return props.previewUrl;
  }

  return `${useApiPath()}${props.previewUrl}`;
});

const clearPreview = () => {
  if (objectPreviewUrl.value) {
    URL.revokeObjectURL(objectPreviewUrl.value);
    objectPreviewUrl.value = null;
  }

  imagePreview.value = null;
};

const handleImageChange = (event: Event) => {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0] ?? null;

  model.value = file;

  clearPreview();

  if (file) {
    objectPreviewUrl.value = URL.createObjectURL(file);
    imagePreview.value = objectPreviewUrl.value;
  }

  input.value = "";
};

const removeImage = () => {
  model.value = null;
  clearPreview();
};

watch(
  model,
  (file) => {
    clearPreview();

    if (file) {
      objectPreviewUrl.value = URL.createObjectURL(file);
      imagePreview.value = objectPreviewUrl.value;
      return;
    }

    imagePreview.value = resolvedPreviewUrl.value;
  },
  { immediate: true },
);

watch(resolvedPreviewUrl, (previewUrl) => {
  if (!model.value) {
    imagePreview.value = previewUrl;
  }
});

onBeforeUnmount(() => {
  clearPreview();
});
</script>

<template>
  <div class="space-y-4">
    <div
      v-if="imagePreview"
      :class="[
        'relative overflow-hidden rounded-md border',
        error
          ? 'border-transparent ring ring-inset ring-error'
          : 'border-default',
      ]"
    >
      <img
        :src="imagePreview"
        :alt="previewAlt ?? 'Image preview'"
        class="h-48 w-full object-cover"
      />

      <UButton
        icon="i-lucide-x"
        color="error"
        variant="solid"
        size="xs"
        square
        class="absolute right-2 top-2"
        @click="removeImage"
      />
    </div>

    <label
      v-else
      :class="[
        'flex min-h-48 cursor-pointer flex-col items-center justify-center rounded-md border bg-elevated/30 px-4 py-6 text-center transition hover:bg-elevated/60',
        error
          ? 'border-0 ring ring-inset ring-error'
          : 'border-dashed border-default',
      ]"
    >
      <UIcon name="i-lucide-image-plus" class="size-8 text-muted" />

      <span class="mt-3 text-sm font-medium">
        {{ label ?? "Upload image" }}
      </span>

      <span class="mt-1 text-xs text-muted">
        {{ hint ?? "PNG, JPG, WEBP up to your backend limit" }}
      </span>

      <input
        type="file"
        accept="image/*"
        class="sr-only"
        @change="handleImageChange"
      />
    </label>

    <div v-if="model" class="rounded-md bg-elevated/50 p-3 text-sm">
      <p class="truncate font-medium">
        {{ model.name }}
      </p>

      <p class="text-muted">{{ Math.round(model.size / 1024) }} KB</p>
    </div>
  </div>
</template>
