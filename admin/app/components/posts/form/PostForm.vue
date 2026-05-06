<script setup lang="ts">
import PostContentEditor from "~/components/editor/PostContentEditor.vue";
import {generateSlug} from "~/utils/slug";
import {
  type CreatePostCredentials,
  createPostSchema,
  type PostResource,
  PostStatus,
  type UpdatePostCredentials,
  updatePostSchema,
} from "~/resources/post";
import type {FormSubmitEvent} from '#ui/types'
import ImageUploadField from "~/components/ImageUploadField.vue";

type PostFormMode = 'create' | 'edit'
type PostFormState = CreatePostCredentials | UpdatePostCredentials

const props = withDefaults(defineProps<{
  mode?: PostFormMode
  post?: PostResource
}>(), {
  mode: 'create',
  post: undefined,
})

const toast = useToast()

const postStore = usePostStore();

const { loading } = storeToRefs(postStore);

const isEditMode = computed(() => props.mode === 'edit')
const formSchema = computed(() => isEditMode.value ? updatePostSchema : createPostSchema)

const form = ref()

const state = reactive<PostFormState>({
  title: props.post?.title ?? '',
  slug: props.post?.slug ?? '',
  excerpt: props.post?.excerpt ?? '',
  content: props.post?.content ?? '',
  status: props.post?.status ?? PostStatus.Draft,
  image: null,
})

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

const submitLabel = computed(() => {
  if (isEditMode.value) {
    return 'Update post'
  }

  return state.status === PostStatus.Published ? 'Publish post' : 'Save draft'
})

watch(
    () => state.title,
    (title) => {
      state.slug = generateSlug(title)
    },
)

const validateContent = async () => {
  try {
    await form.value?.validate({
      name: 'content',
    })
  } catch {
  }
}

const handleSubmit = async (event: FormSubmitEvent<PostFormState>) => {
  try {

    if (isEditMode.value) {
      const post = props.post!;

      await postStore.update(post.id, event.data)
    } else {
      await postStore.create(event.data as CreatePostCredentials)
    }

    toast.add({
      title: isEditMode.value ? 'Post updated' : 'Post created',
      description: isEditMode.value
          ? 'The post has been updated successfully.'
          : 'The post has been created successfully.',
      color: 'success',
    })

    await navigateTo('/posts')
  } catch {
    toast.add({
      title: 'Something went wrong',
      description: isEditMode.value
          ? 'Failed to update post.'
          : 'Failed to create post.',
      color: 'error',
    })
  }
}
</script>

<template>
  <UForm
      ref="form"
      :schema="formSchema"
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
                {{ isEditMode ? 'Update the main information for the article.' : 'Write the main information for the article.' }}
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
              v-slot="{ error }"
              label="Content"
              name="content"
              required
          >
            <PostContentEditor
                :error="Boolean(error)"
                v-model="state.content"
                @blur="validateContent"
                @update:model-value="validateContent"
            />
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

          <USeparator/>

          <div class="flex flex-col gap-3">
            <UButton
                type="submit"
                icon="i-lucide-save"
                :loading="loading"
                block
            >
              {{ submitLabel }}
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

        <UFormField
            v-slot="{ error }"
            label="Image"
            name="image"
            :required="!isEditMode"
        >
          <ImageUploadField
              v-model="state.image"
              :error="Boolean(error)"
              label="Upload cover image"
              :hint="isEditMode ? 'Upload a new image to replace the current cover' : 'PNG, JPG, WEBP up to your backend limit'"
              preview-alt="Cover preview"
              :preview-url="props.post?.image_url"
          />
        </UFormField>
      </UCard>
    </aside>
  </UForm>
</template>
