import axios from 'axios';

const storage = window.sessionStorage;

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
        strategy: 'client',
        options: {},
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
      setStrategy: this.setAuthStrategy,
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
    setAuthStrategy(name) {
      console.log(`[Auth Module] setAuthStrategy: ${name}`);

      if (!moduleOptions.strategies[name]) {
        throw new Error(`Strategy "${name}" is not defined!`);
      }

      this.authState.strategy = name;
      this.authState.options = moduleOptions.strategies[name];

      return this;
    },

    // =========================================================================
    // = Login & Logout
    // =========================================================================

    loginWith(strategy, options = {}) {
      return this.setAuthStrategy(strategy).login(options);
    },

    async login(options = {}) {
      if (typeof options.data === 'undefined') {
        options = { data: options };
      }

      options = {
        data: {},
        ...this.authState.options,
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
      storage.removeItem('token');

      return Promise.resolve(true);
    },

    // =========================================================================
    // = User
    // =========================================================================

    async fetchUser(options = {}) {
      options = {
        token: this.authState.token,
        ...this.authState.options,
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
      storage.setItem('strategy', this.authState.strategy);
      axios.defaults.headers.common['Authorization'] = response.config.headers['Authorization'];

      return response;
    },

    async setUserToken(token) {
      if (typeof token !== 'string' || token === '') {
        throw new Error('Invalid Argument');
      }

      this.authState.token = token;
      storage.setItem('token', token);

      return await this.fetchUser();
    },
  },

  async created() {
    // Restore last strategy.
    const storedStrategy = storage.getItem('strategy');
    if (storedStrategy) {
      console.log('[Auth Module] Restore strategy: ' + storedStrategy);

      this.setAuthStrategy(storedStrategy);
    } else {
      this.setAuthStrategy('client');
    }

    // Restore user data.
    const storedToken = storage.getItem('token');
    if (storedToken) {
      console.log('[Auth Module] Restore user data');

      this.authState.token = storedToken;

      try {
        await this.fetchUser();
      } catch (error) {
        storage.removeItem('token');
      }
    }

    this.authState.loaded = true;
    console.log('[Auth Module] created');
  },
};

// =============================================================================
// = Helpers
// =============================================================================

function isAccessibility(value) {
  return typeof value === 'object' && value !== null;
}
