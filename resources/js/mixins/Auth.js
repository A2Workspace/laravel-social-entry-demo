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

// =============================================================================
// = AuthService
// =============================================================================

class AuthService {
  constructor() {
    this.user = null;
    this.token = null;
    this.strategy = moduleOptions.strategies['user'];
  }

  // ===========================================================================
  // = Strategies & Options
  // ===========================================================================

  setStrategy(name) {
    this.strategy = moduleOptions.strategies[name];

    return this;
  }

  // ===========================================================================
  // = AuthService > Login
  // ===========================================================================

  loginWith(strategy, options = {}) {
    return this.setStrategy(strategy).login(options);
  }

  async login(options = {}) {
    if (typeof options.data === 'undefined') {
      options = { data: options };
    }

    options = {
      data: {},
      ...this.strategy,
      ...options,
    };

    let request = axios.request({
      method: 'POST',
      url: `${options.url}/login`,
      data: options.data,
    });

    const response = await request;
    this.token = response.data.access_token;

    return await this.fetchUser(options);
  }

  // ===========================================================================
  // = AuthService > Logout
  // ===========================================================================

  logout() {
    this.user = null;
    this.token = null;

    return Promise.resolve(true);
  }

  // ===========================================================================
  // = AuthService > fetchUser
  // ===========================================================================

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
  }

  async setUserToken(token) {
    this.token = token;

    return await this.fetchUser();
  }

  // ===========================================================================
  // = AuthService > Helpers
  // ===========================================================================

  get loggedIn() {
    return isAccessibility(this.user);
  }
}

// =============================================================================
// = Utils
// =============================================================================

function isAccessibility(value) {
  return typeof value === 'object' && value !== null;
}

// =============================================================================
// = Mixin Module
// =============================================================================

export default {
  provide() {
    return { $auth: this.$auth };
  },

  beforeCreate() {
    this.$auth = new AuthService();
  },
};
