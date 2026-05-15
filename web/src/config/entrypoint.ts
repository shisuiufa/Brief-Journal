export const useApiPath = () => {
  if (import.meta.env.SSR) {
    return import.meta.env.API_BASE_URL || 'http://nginx';
  }

  return import.meta.env.VITE_API_BASE_URL || 'http://localhost';
};
