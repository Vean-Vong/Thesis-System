/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import VueNumber from 'vue-number-animation'
// custom-style 
import '../public/style.scss'


import '@/styles/styles.scss'
import '@core/scss/index.scss'

import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createApp } from 'vue'
const pinia = createPinia()



pinia.use(piniaPluginPersistedstate)
loadFonts()
const app = createApp(App)
app.use(VueNumber)
app.use(vuetify)
app.use(pinia)
app.use(router)
app.mount('#app')
