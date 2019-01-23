/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import lang from 'element-ui/lib/locale/lang/es';
import locale from 'element-ui/lib/locale';
import ElementUI from 'element-ui';

// Vuex
import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
Vue.use(Vuesax)

// Vuex
import store from './store';

// Mixins
import token from './mixins/token';
import loader from './mixins/loader';

window.axios.interceptors.request.use(function (request) {
  // store.commit('setLoaderState', true);
  return request;
}, function (error) {
  // store.commit('setLoaderState', false);
  return Promise.reject(error);
});

window.axios.interceptors.response.use(function (response) {
  // store.commit('setLoaderState', false);
  return response;
}, function (error) {
  // store.commit('setLoaderState', false);
  return Promise.reject(error);
});

/**
 * Load mixins
 */
Vue.mixin(token);
Vue.mixin(loader);

// configure language
locale.use(lang);

Vue.use(ElementUI, {
  size: 'medium'
});

Vue.component('login-component', require('./components/Login/LoginComponent').default);

Vue.component('list-sales', require('./components/Sales/ListComponent').default);
Vue.component('create-sales', require('./components/Sales/CreateComponent').default);
Vue.component('select-service', require('./components/Sales/SelectServiceComponent').default);
Vue.component('receipt-sales', require('./components/Sales/ReceiptComponent').default);

new Vue({
  el: '#app',
  store,
  mixins: [
    token,
    loader
  ],
  data() {
    return {
      primaryColor: "#021C73",
    }
  },
  methods: {
    goto: function (link) {
      window.location.href = link;
    }
  }
});
