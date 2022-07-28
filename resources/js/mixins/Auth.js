import axios from 'axios';

export default {
  data() {
    return {
      user: null,
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
    loginWith(provider, data) {
      let options = { data };

      if (provider === 'admin') {
        options = {
          ...options,
          method: 'post',
          url: '/admin/auth/login',
        };
      } else {
        options = {
          ...options,
          method: 'post',
          url: '/auth/login',
        };
      }

      return axios.request(options).then((response) => {
        let payload = response.data;

        if (isAccessibility(payload.data)) {
          payload = payload.data;
        }

        if (isAccessibility(payload.user)) {
          this.user = payload.user;
        }

        return response;
      });
    },

    loggout() {
      return new Promise((resolve) => {
        this.user = null;
        resolve();
      });
    },
  },

  computed: {
    isLoggedIn() {
      return typeof this.user === 'object' && this.user != null;
    },
  },
};

function isAccessibility(value) {
  return typeof value === 'object' && value !== null;
}
