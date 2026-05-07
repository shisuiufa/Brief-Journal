<script setup lang="ts">
import type { FormSubmitEvent } from "#ui/types";
import type {
  TaxonomyCredentials,
  TaxonomyResource,
  TaxonomyType,
} from "~/resources/taxonomy";
import { taxonomyFormSchema } from "~/resources/taxonomy";
import { generateSlug } from "~/utils/slug";

const props = withDefaults(
  defineProps<{
    type: TaxonomyType;
    item?: TaxonomyResource | null;
    loading?: boolean;
  }>(),
  {
    item: null,
    loading: false,
  },
);

const emit = defineEmits<{
  submit: [credentials: TaxonomyCredentials];
  cancel: [];
}>();

const form = ref();

const state = reactive<TaxonomyCredentials>({
  name: "",
  slug: "",
});

const isEditMode = computed(() => Boolean(props.item));

const title = computed(() => {
  const resource = props.type === "category" ? "category" : "tag";

  return isEditMode.value ? `Edit ${resource}` : `Create ${resource}`;
});

const description = computed(() => {
  if (props.type === "category") {
    return "Categories group posts into editorial sections.";
  }

  return "Tags describe topics and technologies used in posts.";
});

const submitLabel = computed(() => {
  if (isEditMode.value) {
    return props.type === "category" ? "Update category" : "Update tag";
  }

  return props.type === "category" ? "Create category" : "Create tag";
});

watch(
  () => props.item,
  (item) => {
    state.name = item?.name ?? "";
    state.slug = item?.slug ?? "";
  },
  {
    immediate: true,
  },
);

watch(
  () => state.name,
  (name) => {
    if (isEditMode.value) {
      return;
    }

    state.slug = generateSlug(name);
  },
);

const handleSubmit = (event: FormSubmitEvent<TaxonomyCredentials>) => {
  emit("submit", event.data);
};

const handleCancel = () => {
  form.value?.clear();
  emit("cancel");
};
</script>

<template>
  <UCard>
    <template #header>
      <div>
        <h2 class="text-base font-semibold">
          {{ title }}
        </h2>

        <p class="mt-1 text-sm text-muted">
          {{ description }}
        </p>
      </div>
    </template>

    <UForm
      ref="form"
      :schema="taxonomyFormSchema"
      :state="state"
      class="space-y-5"
      @submit="handleSubmit"
    >
      <UFormField label="Name" name="name" required>
        <UInput
          v-model="state.name"
          icon="i-lucide-type"
          placeholder="Laravel"
          class="w-full"
        />
      </UFormField>

      <UFormField label="Slug" name="slug" required>
        <UInput
          v-model="state.slug"
          icon="i-lucide-link"
          placeholder="laravel"
          class="w-full"
        />
      </UFormField>

      <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        <UButton
          v-if="isEditMode"
          color="neutral"
          variant="soft"
          type="button"
          @click="handleCancel"
        >
          Cancel
        </UButton>

        <UButton type="submit" icon="i-lucide-save" :loading="loading">
          {{ submitLabel }}
        </UButton>
      </div>
    </UForm>
  </UCard>
</template>
