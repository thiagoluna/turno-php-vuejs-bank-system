import Vue from "vue";
import VMask from "v-mask";
import Snotify from "vue-snotify";

Vue.use(VMask);
Vue.use(Snotify, {toast: {showProgressBar: false}});

require('./bootstrap');
window.Vue = require('vue').default;

import router from './routes/routes';
import store from './vuex/store'

Vue.component('admin-component', require('./components/admin/AdminComponent.vue').default);
Vue.component('preloader-component', require('./components/layout/PreloaderComponent.vue').default);

const app = new Vue({
    router,
    store,
    el: '#app',
});

store.dispatch('loadDeposits');

store.dispatch('checkLogin')
    .then(() => router.push({ name: store.state.auth.urlBack }))
    .catch(() => router.push({ name: 'signup' }))
