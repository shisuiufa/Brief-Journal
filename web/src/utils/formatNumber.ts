const compactNumberFormatter = new Intl.NumberFormat('en-US', {
  notation: 'compact',
  maximumFractionDigits: 1,
})

export const formatCompactNumber = (value: number): string => {
  return compactNumberFormatter.format(value)
}

export const formatViews = (views: number): string => {
  return `${formatCompactNumber(views)} views`
}
