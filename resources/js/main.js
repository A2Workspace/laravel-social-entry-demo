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
 * = Create Vue Application
 * =============================================================================
 **/

const App = importDefault(require('./App'));

window.app = new Vue({
  el: '#app',
  render: (h) => h(App),
});
