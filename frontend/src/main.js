import { createApp } from 'vue';

import { createPinia } from 'pinia';
import piniaPersistedState from 'pinia-plugin-persistedstate';
import { createVuetify } from 'vuetify';
import 'vuetify/styles';

import '@mdi/font/css/materialdesignicons.css'

import './style.css';
import router from './routers/router.js';
import authStore from '@stores/authStore.js';
import axios from '@api/api.js';

import App from './App.vue';

const vuetify = createVuetify();
const app = createApp(App);


app.use(router);
app.use(createPinia().use(piniaPersistedState)); // gerenciador de estados
app.use(vuetify);



//app.config.globalProperties.$axios = axios;
//app.config.globalProperties.$pinia = authStore();

app.provide('axios', axios);
app.provide('pinia', authStore());
app.provide('mobile', /Mobi|Android|Iphone/.test(navigator.userAgent));




app.mount('#app');
