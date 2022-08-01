import axios from 'axios';

const moduleOptions = {
  strategies: {
    user: {
      url: '/auth',
    },
    admin: {
      url: '/admin/auth',
    },
  },
};

export default {
  data() {
    return {
      authState: {
        user: null,
        token: null,
        strategy: moduleOptions.strategies['user'],
      },
    };
  },

  computed: {
    loggedIn() {
      return isAccessibility(this.authState.user);
    },

    user() {
      return this.authState.user;
    },
  },

  provide() {
    const $auth = {
      login: this.login,
      loginWith: this.loginWith,
      loggout: this.loggout,
      loggedIn: () => this.loggedIn,
      user: () => this.user,
    };

    return { $auth };
  },

  methods: {
    setStrategy(name) {
      this.authState.strategy = moduleOptions.strategies[name];

      return this;
    },

    // =========================================================================
    // = Login & Logout
    // =========================================================================

    loginWith(strategy, options = {}) {
      return this.setStrategy(strategy).login(options);
    },

    async login(options = {}) {
      if (typeof options.data === 'undefined') {
        options = { data: options };
      }

      options = {
        data: {},
        ...this.authState.strategy,
        ...options,
      };

      let request = axios.request({
        method: 'POST',
        url: `${options.url}/login`,
        data: options.data,
      });

      const response = await request;
      this.authState.token = response.data.access_token;

      return await this.fetchUser(options);
    },

    logout() {
      this.authState.user = null;
      this.authState.token = null;

      return Promise.resolve(true);
    },

    // =========================================================================
    // = User
    // =========================================================================

    async fetchUser(options = {}) {
      options = {
        token: this.authState.token,
        ...this.authState.strategy,
        ...options,
      };

      if (options.token === null) {
        throw new Error('Invalid user token');
      }

      const response = await axios.request({
        method: 'GET',
        url: `${options.url}/user`,
        headers: {
          Authorization: `Bearer ${options.token}`,
        },
      });

      let userData = response.data;
      if (isAccessibility(userData.data)) {
        userData = userData.data;
      }

      this.authState.user = userData;

      return response;
    },

    async setUserToken(token) {
      this.authState.token = token;

      return await this.fetchUser();
    },
  },
};

function isAccessibility(value) {
  return typeof value === 'object' && value !== null;
}
