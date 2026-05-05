<script setup lang="ts">
import type { Editor } from '@tiptap/vue-3'
import type { EditorCustomHandlers, EditorToolbarItem } from '@nuxt/ui'
import EditorImageUploadExtension from './extensions/EditorImageUploadExtension'

const model = defineModel<string>({
  default: '',
})

const customHandlers = {
  imageUpload: {
    canExecute: (editor: Editor) => {
      return editor.can().insertContent({ type: 'imageUpload' })
    },
    execute: (editor: Editor) => {
      return editor.chain().focus().insertImageUpload().run()
    },
    isActive: (editor: Editor) => {
      return editor.isActive('imageUpload')
    },
    isDisabled: undefined,
  },
} satisfies EditorCustomHandlers

const items = [
  [
    {
      kind: 'imageUpload',
      icon: 'i-lucide-image',
      label: 'Add image',
      variant: 'soft',
    },
  ],
  [
    {
      icon: 'i-lucide-heading',
      content: {
        align: 'start',
      },
      items: [
        {
          kind: 'heading',
          level: 1,
          icon: 'i-lucide-heading-1',
          label: 'Heading 1',
        },
        {
          kind: 'heading',
          level: 2,
          icon: 'i-lucide-heading-2',
          label: 'Heading 2',
        },
        {
          kind: 'heading',
          level: 3,
          icon: 'i-lucide-heading-3',
          label: 'Heading 3',
        },
        {
          kind: 'heading',
          level: 4,
          icon: 'i-lucide-heading-4',
          label: 'Heading 4',
        },
      ],
    },
  ],
  [
    {
      kind: 'mark',
      mark: 'bold',
      icon: 'i-lucide-bold',
    },
    {
      kind: 'mark',
      mark: 'italic',
      icon: 'i-lucide-italic',
    },
    {
      kind: 'mark',
      mark: 'underline',
      icon: 'i-lucide-underline',
    },
    {
      kind: 'mark',
      mark: 'strike',
      icon: 'i-lucide-strikethrough',
    },
    {
      kind: 'mark',
      mark: 'code',
      icon: 'i-lucide-code',
    },
  ],
] satisfies EditorToolbarItem<typeof customHandlers>[][]
</script>

<template>
  <UEditor
      v-slot="{ editor }"
      v-model="model"
      :extensions="[EditorImageUploadExtension]"
      :handlers="customHandlers"
      content-type="html"
      :ui="{
      base: 'min-h-74 p-6 sm:px-10 focus:outline-none'
    }"
      class="w-full overflow-hidden rounded-xl border border-default bg-default"
  >
    <UEditorToolbar
        :editor="editor"
        :items="items"
        class="border-b border-default bg-elevated/40 px-4 py-2 overflow-x-auto"
    />
  </UEditor>
</template>