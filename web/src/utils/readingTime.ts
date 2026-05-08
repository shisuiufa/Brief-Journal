const WORDS_PER_MINUTE = 220

export const getReadingTime = (content: string): string => {
  const words = content
    .replace(/<[^>]*>/g, ' ')
    .trim()
    .split(/\s+/)
    .filter(Boolean).length

  return `${Math.max(1, Math.ceil(words / WORDS_PER_MINUTE))} min read`
}
