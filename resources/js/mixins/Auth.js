import axios from 'axios';

export default {
  data() {
    return {
      user: null,
      token: null,
    };
  },

  provide() {
    return {
      loginWith: this.loginWith,
      loggout: this.loggout,
      isLoggedIn: () => this.isLoggedIn,
      user: () => this.user,
    };
  },

  methods: {
    async loginWith(provider, data) {
      let options = {};

      if (provider === 'admin') {
        options = {
          endpoint: '/admin/auth',
          // ...
        };
      } else {
        options = {
          endpoint: '/auth',
          // ...
        };
      }

      const response = await axios.request({
        method: 'post',
        url: `${options.endpoint}/login`,
        data,
      });

      this.token = response.data.access_token;

      return this.fetchUser(options);
    },

    async fetchUser(options = {}) {
      if (this.token === null) {
        return;
      }

      options = {
        endpoint: '/auth',
        ...options,
      };

      const { token } = this;

      const response = await axios.request({
        method: 'get',
        url: `${options.endpoint}/user`,
        headers: {
          Authorization: `Bearer ${token}`,
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
