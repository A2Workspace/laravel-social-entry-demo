<template>
  <div class="login-page">
    <LoginSection headerText="Login As Administrator"></LoginSection>
  </div>
</template>

<script>
import BaseLoginPage from '../client/login';

export default {
  extends: BaseLoginPage,

  methods: {
    /**
     * @override
     */
    handleLogin(options) {
      return this.$auth.loginWith('admin', options);
    },
  },
};
</script>
