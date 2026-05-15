import './assets/main.css'
import { createHead } from '@unhead/vue/client'
import { createApp } from './create-app'
import { createSocketPlugin } from './plugins/socket'

const { app, router } = createApp(window.__INITIAL_STATE__ ?? {})
const head = createHead()

app.use(head)
app.use(createSocketPlugin())

router.isReady().then(() => {
  app.mount('#app')
})
