/* eslint-disable import/extensions */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/order */
// import '@/@fake-db/db'
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import i18n from '@/plugins/i18n'
import layoutsPlugin from '@/plugins/layouts'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

import '../public/font.css'

import functions from './plugins/mixin/functions'

// import { abilitiesPlugin } from '@casl/vue'
import '@core/scss/template/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'

import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createApp } from 'vue'

// Custom Component
import AppFormCreateTemplate from './components/AppFormCreateTemplate.vue'
import AppFormUpdateTemplate from './components/AppFormUpdateTemplate.vue'
import AppSubmitButton from './components/AppSubmitButton.vue'
import AppBackButton from './components/AppBackButton.vue'

loadFonts()

const pinia = createPinia()

// Create vue app
const app = createApp(App)

pinia.use(piniaPluginPersistedstate)

// Use plugins
app.use(vuetify)
app.use(pinia)
app.use(router)
app.use(layoutsPlugin)
app.use(i18n)
app.mixin(functions)
app.component('AppFormCreateTemplate', AppFormCreateTemplate)
app.component('AppFormUpdateTemplate', AppFormUpdateTemplate)
app.component('AppBackButton', AppBackButton)
app.component('AppSubmitButton', AppSubmitButton)
app.component('VueDatePicker', VueDatePicker)

// Mount vue app
app.mount('#app')
