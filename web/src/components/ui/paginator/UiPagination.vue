<script setup lang="ts">
import { computed } from 'vue'
import UiPaginationEllipsis from './UiPaginationEllipsis.vue'
import UiPaginationNavButton from './UiPaginationNavButton.vue'
import UiPaginationPageButton from './UiPaginationPageButton.vue'
import type { PaginationItem } from '@/types/pagination.ts'

const props = withDefaults(
  defineProps<{
    page: number
    totalPages: number
    siblingCount?: number
  }>(),
  {
    siblingCount: 1,
  },
)

const emit = defineEmits<{
  'update:page': [value: number]
}>()

const items = computed<PaginationItem[]>(() => {
  const current = props.page
  const total = props.totalPages
  const sibling = props.siblingCount

  if (total <= 1) {
    return [1]
  }

  if (total <= 7) {
    return Array.from({ length: total }, (_, index) => index + 1)
  }

  const firstPage = 1
  const lastPage = total

  const leftSibling = Math.max(current - sibling, 2)
  const rightSibling = Math.min(current + sibling, total - 1)

  const showLeftEllipsis = leftSibling > 2
  const showRightEllipsis = rightSibling < total - 1

  const result: PaginationItem[] = [firstPage]

  if (showLeftEllipsis) {
    result.push('ellipsis')
  } else {
    for (let page = 2; page < leftSibling; page += 1) {
      result.push(page)
    }
  }

  for (let page = leftSibling; page <= rightSibling; page += 1) {
    result.push(page)
  }

  if (showRightEllipsis) {
    result.push('ellipsis')
  } else {
    for (let page = rightSibling + 1; page < lastPage; page += 1) {
      result.push(page)
    }
  }

  result.push(lastPage)

  return result
})

const setPage = (nextPage: number) => {
  if (nextPage < 1 || nextPage > props.totalPages || nextPage === props.page) {
    return
  }

  emit('update:page', nextPage)
}

const goToPrevPage = () => {
  setPage(props.page - 1)
}

const goToNextPage = () => {
  setPage(props.page + 1)
}
</script>

<template>
  <nav class="flex items-center gap-1 lg:gap-3" aria-label="Posts pagination">
    <UiPaginationNavButton direction="prev" :disabled="page === 1" @click="goToPrevPage" />

    <template v-for="(item, index) in items" :key="`${item}-${index}`">
      <UiPaginationEllipsis v-if="item === 'ellipsis'" />

      <UiPaginationPageButton v-else :page="item" :active="item === page" @click="setPage(item)" />
    </template>

    <UiPaginationNavButton direction="next" :disabled="page === totalPages" @click="goToNextPage" />
  </nav>
</template>
