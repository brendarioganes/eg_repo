import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

// Create Pinia store
const pinia = createPinia()
app.use(pinia)
app.use(router)

// Mount the app
app.mount('#app')

// Handle any unhandled promise rejections
window.addEventListener('unhandledrejection', (event) => {
  console.error('Unhandled promise rejection:', event.reason)
})

// Handle any uncaught errors
window.addEventListener('error', (event) => {
  console.error('Uncaught error:', event.error)
})
