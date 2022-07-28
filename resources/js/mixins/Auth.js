import axios from 'axios';

export default {
  data() {
    return {
      user: null,
    };
  },

  provide() {
    return {
      login: this.login,
      loggout: this.loggout,
      isLoggedIn: () => this.isLoggedIn,
      user: () => this.user,
    };
  },

  methods: {
    login(data) {
      return axios
        .request({
          method: 'post',
          url: '/api/auth/login',
          data,
        })
        .then((res) => {});
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
