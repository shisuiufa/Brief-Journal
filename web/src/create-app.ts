import { createPinia } from 'pinia'
import { createSSRApp } from 'vue'
import App from './App.vue'
import { createApiPlugin } from './plugins/api'
import { createAppRouter } from './router'

export function createApp() {
  const app = createSSRApp(App)
  const router = createAppRouter()
  const pinia = createPinia()

  app.use(router)
  app.use(pinia)
  app.use(createApiPlugin())

  return {
    app,
    router,
    pinia,
  }
}
