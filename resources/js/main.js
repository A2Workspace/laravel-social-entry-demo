import Vue from 'vue';

function createApp(rootComponent) {
  return new Vue({
    render: (h) => h(rootComponent),
  });
}

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

import App from './App';

createApp(App).$mount('#app');
