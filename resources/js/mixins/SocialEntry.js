import axios from 'axios';

export default {
  data() {
    return {
      //
    };
  },

  provide() {
    const $socialEntry = {
      authorize: this.authorize,
    };

    return { $socialEntry };
  },

  methods: {
    authorize() {
      return {
        redirect() {},
      };
    },
  },
};
