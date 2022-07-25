import Vue from 'vue';

/* =============================================================================
 * = Axios
 * =============================================================================
 **/

const axios = require("axios");

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/* =============================================================================
 * = Including Components
 * =============================================================================
 **/

Vue.component("Login", require("./components/Login").default);
Vue.component("Profile", require("./components/Profile").default);

/* =============================================================================
 * = Create The Vue Application
 * =============================================================================
 **/

const Root = require("./components/Root");

window.app = new Vue({
  el: "#app",
  render: (h) => h(Root.default),
});
