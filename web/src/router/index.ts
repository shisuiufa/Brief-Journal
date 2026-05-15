import HomeView from '@/views/HomeView.vue';
import NotFoundView from '@/views/NotFoundView.vue';
import PostView from '@/views/PostView.vue';
import { createMemoryHistory, createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: { layout: 'default' },
  },
  {
    path: '/posts/:slug',
    name: 'post',
    component: PostView,
    meta: { layout: 'default' },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFoundView,
    meta: { layout: 'default' },
  },
];

export function createAppRouter() {
  return createRouter({
    history: import.meta.env.SSR
      ? createMemoryHistory(import.meta.env.BASE_URL)
      : createWebHistory(import.meta.env.BASE_URL),
    routes,
  });
}
