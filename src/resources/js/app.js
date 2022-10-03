require('./bootstrap');

import { createApp } from 'vue';
import App from './views/app.vue'

const mountEl = document.querySelector("#gallery-app");

createApp(App, {...mountEl.dataset}).mount("#gallery-app")
