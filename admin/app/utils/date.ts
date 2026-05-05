export const formatDate = (value: string | null | undefined) => {
    if(value == null || undefined) {
        return value;
    }
    return new Intl.DateTimeFormat('en', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(new Date(value ?? ''))
}