export const formatDate = (value: string | null | undefined) => {
    return new Intl.DateTimeFormat('en', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(new Date(value ?? ''))
}