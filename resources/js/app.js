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

Vue.component('create-items', require('./components/Items/CreateComponent').default);
Vue.component('edit-items', require('./components/Items/EditComponent').default);
Vue.component('list-items', require('./components/Items/ListComponent').default);

Vue.component('create-services', require('./components/Services/CreateComponent').default);
Vue.component('edit-services', require('./components/Services/EditComponent').default);
Vue.component('list-services', require('./components/Services/ListComponent').default);

Vue.component('create-cars', require('./components/Cars/CreateComponent').default);
Vue.component('edit-cars', require('./components/Cars/EditComponent').default);
Vue.component('list-cars', require('./components/Cars/ListComponent').default);

Vue.component('create-clients', require('./components/Clients/CreateComponent').default);
Vue.component('edit-clients', require('./components/Clients/EditComponent').default);
Vue.component('list-clients', require('./components/Clients/ListComponent').default);

Vue.component('create-carservices', require('./components/CarServices/CreateComponent').default);
Vue.component('list-carservices', require('./components/CarServices/ListComponent').default);
Vue.component('select-item', require('./components/CarServices/SelectItemComponent').default);

Vue.component('create-users', require('./components/Users/CreateComponent').default);
Vue.component('edit-users', require('./components/Users/EditComponent').default);
Vue.component('list-users', require('./components/Users/ListComponent').default);

Vue.component('create-roles', require('./components/Roles/CreateComponent').default);
Vue.component('edit-roles', require('./components/Roles/EditComponent').default);
Vue.component('list-roles', require('./components/Roles/ListComponent').default);

Vue.component('datepicker', require('./components/Dashboard/DatepickerComponent').default);
Vue.component('top-numbers', require('./components/Dashboard/TopNumbersComponent').default);
Vue.component('dashboard-chart', require('./components/Dashboard/ChartComponent').default);
Vue.component('dashboard-table', require('./components/Dashboard/TableComponent').default);

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