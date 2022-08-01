import axios from 'axios';

export default {
  data() {
    return {
      user: null,
      token: null,
    };
  },

  provide() {
    const $auth = {
      login: this.login,
      loginWith: this.loginWith,
      loggout: this.loggout,
      isLoggedIn: () => this.isLoggedIn,
      user: () => this.user,
    };

    return { $auth };
  },

  methods: {
    loginWith(provider, options) {
      if (typeof options.data === 'undefined') {
        options = { data: options };
      }

      if (provider === 'admin') {
        options.endpoint = '/admin/auth';
      }

      return this.login(options);
    },

    async login(options) {
      options = {
        method: 'post',
        endpoint: '/auth',
        data: {},
        ...options,
      };

      const response = await axios.request({
        method: options.method,
        url: `${options.endpoint}/login`,
        data: options.data,
      });

      this.token = response.data.access_token;

      return this.fetchUser(options);
    },

    async fetchUser(options = {}) {
      options = {
        endpoint: '/auth',
        token: this.token,
        ...options,
      };

      if (options.token === null) {
        throw new Error('Invalid user token');
      }

      const response = await axios.request({
        method: 'get',
        url: `${options.endpoint}/user`,
        headers: {
          Authorization: `Bearer ${options.token}`,
        },
      });

      let userData = response.data;
      if (isAccessibility(userData.data)) {
        userData = userData.data;
      }

      this.user = userData;

      return response;
    },

    async loggout() {
      this.user = null;
      this.token = null;
    },

    setUserToken(token) {
      this.token = token;

      return this.fetchUser();
    },
  },

  computed: {
    isLoggedIn() {
      return isAccessibility(this.user);
    },
  },
};

function isAccessibility(value) {
  return typeof value === 'object' && value !== null;
}
