import { createHead } from '@unhead/vue/server';
import { renderToString } from '@vue/server-renderer';
import { createApp } from './create-app';
import { getCallOnceKeys } from './plugins/call-once';

export async function render(url: string) {
  const { app, router, pinia, callOnce } = createApp();
  const head = createHead();

  app.use(head);

  await router.push(url);
  await router.isReady();
  const route = router.currentRoute.value;

  const html = await renderToString(app);
  const postState = pinia.state.value.post as
    | { currentPostNotFoundSlug?: string | null }
    | undefined;

  const routeSlug = Array.isArray(route.params.slug) ? route.params.slug[0] : route.params.slug;

  const isPostNotFound = route.name === 'post' && postState?.currentPostNotFoundSlug === routeSlug;

  const status = route.name === 'not-found' || isPostNotFound ? 404 : 200;

  return {
    html,
    status,
    head,
    state: {
      pinia: pinia.state.value,
      callOnce: getCallOnceKeys(callOnce),
    },
  };
}
