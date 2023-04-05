import './bootstrap'
import { createApp } from 'vue/dist/vue.esm-bundler'
import App from './web/App.vue'
import RouterWeb from './router/index'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import NavbarComponent from '@/Components/Navbar.vue'
import VueSweetalert2 from 'vue-sweetalert2'
import '@sweetalert2/theme-dark/dark.min.css'

const app = createApp(App)
const vuetify = createVuetify({
	components,
	directives
})

app.use(RouterWeb)
app.use(vuetify)
app.use(VueSweetalert2)
app.component('Navbar', NavbarComponent)
app.mount('#app')