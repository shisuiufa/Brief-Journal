import { useColorMode } from '@vueuse/core'

export const useTheme = () => {
  const mode = useColorMode({
    attribute: 'class',
  })

  const toggleTheme = () => {
    mode.value = mode.value === 'dark' ? 'light' : 'dark'
  }

  return {
    mode,
    toggleTheme,
  }
}
