import { createPinia } from 'pinia';
import { createSSRApp } from 'vue';
import App from './App.vue';
import { createApiPlugin } from './plugins/api';
import { createCallOnceContext, createCallOncePlugin } from './plugins/call-once';
import { createAppRouter } from './router';
import type { InitialState } from './types/initial-state';

export function createApp(initialState: InitialState = {}) {
  const app = createSSRApp(App);
  const router = createAppRouter();
  const pinia = createPinia();
  const callOnce = createCallOnceContext(initialState.callOnce ?? []);

  app.use(router);
  app.use(pinia);
  app.use(createApiPlugin());
  app.use(createCallOncePlugin(callOnce));

  if (initialState.pinia) {
    pinia.state.value = initialState.pinia;
  }

  return {
    app,
    router,
    pinia,
    callOnce,
  };
}
