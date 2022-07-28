import axios from 'axios';

export default {
  data() {
    return {
      //
    };
  },

  provide() {
    return {
      authorize: this.authorize,
    };
  },

  methods: {
    authorize() {
      return {
        redirect() {},
      };
    },
  },
};
