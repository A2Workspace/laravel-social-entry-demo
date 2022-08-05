import axios from 'axios';

const moduleOptions = {
  strategies: {
    client: {
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
        loaded: false,
        user: null,
        token: null,
        strategy: moduleOptions.strategies['client'],
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
      logout: this.logout,
      setUserToken: this.setUserToken,
      isLoggedIn: () => this.loggedIn,
      getUser: () => this.user,
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

      return await this.setUserToken(response.data.access_token);
    },

    logout() {
      this.authState.user = null;
      this.authState.token = null;

      axios.defaults.headers.common['Authorization'] = null;
      window.sessionStorage.removeItem('token');

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
        headers: {
          Authorization: `Bearer ${options.token}`,
        },
        method: 'GET',
        url: `${options.url}/user`,
      });

      let userData = response.data;
      if (isAccessibility(userData.data)) {
        userData = userData.data;
      }

      this.authState.user = userData;
      axios.defaults.headers.common['Authorization'] = response.config.headers['Authorization'];

      return response;
    },

    async setUserToken(token) {
      if (typeof token !== 'string' || token === '') {
        throw new Error('Invalid Argument');
      }

      this.authState.token = token;
      window.sessionStorage.setItem('token', token);

      return await this.fetchUser();
    },
  },

  async created() {
    const token = window.sessionStorage.getItem('token');

    // Retrieve the user data from stored token.
    if (token) {
      this.authState.token = token;

      try {
        await this.fetchUser();
      } catch (error) {
        window.sessionStorage.removeItem('token');
      }
    }

    this.authState.loaded = true;
  },
};

function isAccessibility(value) {
  return typeof value === 'object' && value !== null;
}
