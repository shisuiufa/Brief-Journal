import './assets/main.css'

import { createHead } from '@unhead/vue/client'
import { createApp } from './create-app'

const { app, router } = createApp(window.__INITIAL_STATE__ ?? {})
const head = createHead()

app.use(head)

router.isReady().then(() => {
  app.mount('#app')
})
