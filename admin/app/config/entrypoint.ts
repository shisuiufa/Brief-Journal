export const useApiPath = () => {
    const config = useRuntimeConfig();
    return config.public?.apiBaseUrl  || '';
};
