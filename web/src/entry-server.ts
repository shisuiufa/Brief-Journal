import { createHead } from '@unhead/vue/server'
import { renderToString } from '@vue/server-renderer'
import { createApp } from './create-app'
import { getCallOnceKeys } from './plugins/call-once'

export async function render(url: string) {
  const { app, router, pinia, callOnce } = createApp()
  const head = createHead()

  app.use(head)

  await router.push(url)
  await router.isReady()

  const html = await renderToString(app)
  const status = router.currentRoute.value.name === 'not-found' ? 404 : 200

  return {
    html,
    status,
    head,
    state: {
      pinia: pinia.state.value,
      callOnce: getCallOnceKeys(callOnce),
    },
  }
}
