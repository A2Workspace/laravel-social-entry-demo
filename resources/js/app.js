import Vue from 'vue';

function importDefault(esModel) {
  return esModel.default || esModel;
}

/* =============================================================================
 * = Axios
 * =============================================================================
 **/

const axios = require('axios');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/* =============================================================================
 * = Including Components
 * =============================================================================
 **/

Vue.component('Login', importDefault(require('./components/Login')));
Vue.component('Profile', importDefault(require('./components/Profile')));

/* =============================================================================
 * = Create The Vue Application
 * =============================================================================
 **/

const Root = importDefault(require('./Root'));

window.app = new Vue({
  el: '#app',
  render: (h) => h(Root),
});
