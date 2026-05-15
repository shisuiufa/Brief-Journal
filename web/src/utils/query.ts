import type { LocationQueryValue } from 'vue-router';

type QueryValue = LocationQueryValue | LocationQueryValue[] | undefined;

export const getStringQuery = (value: QueryValue) => {
  return typeof value === 'string' ? value : undefined;
};

export const getNumberQuery = (value: QueryValue) => {
  if (typeof value !== 'string') {
    return undefined;
  }

  const number = Number(value);

  return Number.isFinite(number) ? number : undefined;
};
