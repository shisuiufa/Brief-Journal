<script setup lang="ts">
import PostContentEditor from "~/components/editor/PostContentEditor.vue";
import {generateSlug} from "~/utils/slug";
import {type CreatePostCredentials, createPostSchema, PostStatus} from "~/resources/post";
import type { FormSubmitEvent } from '#ui/types'

const toast = useToast()

const postStore = usePostStore();

const state = reactive<CreatePostCredentials>({
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  status: PostStatus.Draft,
  image: null,
})

const imagePreview = ref<string | null>(null)

const statusItems = [
  {
    label: 'Draft',
    value: PostStatus.Draft,
    icon: 'i-lucide-file-pen-line',
  },
  {
    label: 'Published',
    value: PostStatus.Published,
    icon: 'i-lucide-circle-check',
  },
]

watch(
    () => state.title,
    (title) => {
      state.slug = generateSlug(title)
    },
)

const handleImageChange = (event: Event) => {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0] ?? null

  state.image = file

  if (imagePreview.value) {
    URL.revokeObjectURL(imagePreview.value)
  }

  imagePreview.value = file ? URL.createObjectURL(file) : null
}

const removeImage = () => {
  state.image = null

  if (imagePreview.value) {
    URL.revokeObjectURL(imagePreview.value)
    imagePreview.value = null
  }
}

onBeforeUnmount(() => {
  if (imagePreview.value) {
    URL.revokeObjectURL(imagePreview.value)
  }
})

const handleSubmit = async (event: FormSubmitEvent<CreatePostCredentials>) => {
  try {
    await postStore.create(event.data);

    toast.add({
      title: 'Post created',
      description: 'The post has been created successfully.',
      color: 'success',
    })

    await navigateTo('/posts')
  } catch {
    toast.add({
      title: 'Something went wrong',
      description: 'Failed to create post.',
      color: 'error',
    })
  }
}
</script>

<template>
  <UForm
      :schema="createPostSchema"
      :state="state"
      class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
      @submit="handleSubmit"
  >
    <div class="space-y-6">
      <UCard>
        <template #header>
          <div class="flex items-start justify-between gap-4">
            <div>
              <h2 class="text-base font-semibold">
                Post content
              </h2>

              <p class="mt-1 text-sm text-muted">
                Write the main information for the article.
              </p>
            </div>

            <UBadge
                :color="getPostStatusColor(state.status)"
                variant="soft"
            >
              {{ getPostStatusLabel(state.status) }}
            </UBadge>
          </div>
        </template>

        <div class="space-y-5">
          <UFormField
              label="Title"
              name="title"
              required
          >
            <UInput
                v-model="state.title"
                size="lg"
                placeholder="Laravel Sanctum SPA authentication"
                icon="i-lucide-heading-1"
                class="w-full"
            />
          </UFormField>

          <UFormField
              label="Slug"
              name="slug"
              required
              help="Used in the public post URL."
          >
            <UInput
                v-model="state.slug"
                placeholder="laravel-sanctum-spa-authentication"
                icon="i-lucide-link"
                class="w-full"
            />
          </UFormField>

          <UFormField
              label="Excerpt"
              name="excerpt"
              help="Short description for cards and previews."
          >
            <UTextarea
                v-model="state.excerpt"
                placeholder="Short post preview..."
                :rows="3"
                autoresize
                class="w-full"
            />
          </UFormField>

          <UFormField
              label="Content"
              name="content"
              required
          >
            <PostContentEditor v-model="state.content" />
          </UFormField>
        </div>
      </UCard>
    </div>

    <aside class="space-y-6 xl:sticky xl:top-6 xl:self-start">
      <UCard>
        <template #header>
          <h2 class="text-base font-semibold">
            Publish settings
          </h2>
        </template>

        <div class="space-y-5">
          <UFormField
              label="Status"
              name="status"
              required
          >
            <USelect
                v-model="state.status"
                :items="statusItems"
                class="w-full"
            />
          </UFormField>

          <USeparator />

          <div class="flex flex-col gap-3">
            <UButton
                type="submit"
                icon="i-lucide-save"
                block
            >
              {{ state.status === PostStatus.Published ? 'Publish post' : 'Save draft' }}
            </UButton>

            <UButton
                to="/posts"
                color="neutral"
                variant="soft"
                block
            >
              Cancel
            </UButton>
          </div>
        </div>
      </UCard>

      <UCard>
        <template #header>
          <h2 class="text-base font-semibold">
            Cover image
          </h2>
        </template>

        <div class="space-y-4">
          <div
              v-if="imagePreview"
              class="relative overflow-hidden rounded-xl border border-default"
          >
            <img
                :src="imagePreview"
                alt="Cover preview"
                class="h-48 w-full object-cover"
            >

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
              class="flex min-h-48 cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-default bg-elevated/30 px-4 py-6 text-center transition hover:bg-elevated/60"
          >
            <UIcon
                name="i-lucide-image-plus"
                class="size-8 text-muted"
            />

            <span class="mt-3 text-sm font-medium">
              Upload cover image
            </span>

            <span class="mt-1 text-xs text-muted">
              PNG, JPG, WEBP up to your backend limit
            </span>

            <input
                type="file"
                accept="image/*"
                class="sr-only"
                @change="handleImageChange"
            >
          </label>

          <div
              v-if="state.image"
              class="rounded-lg bg-elevated/50 p-3 text-sm"
          >
            <p class="truncate font-medium">
              {{ state.image.name }}
            </p>

            <p class="text-muted">
              {{ Math.round(state.image.size / 1024) }} KB
            </p>
          </div>
        </div>
      </UCard>
    </aside>
  </UForm>
</template>