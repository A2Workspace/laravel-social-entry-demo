<template>
  <div class="login-page">
    <LoginPane headerText="Login As Admin"></LoginPane>
  </div>
</template>

<script>
import BaseLoginPage, { resetParams, log } from '../client/login';

export default {
  extends: BaseLoginPage,

  methods: {
    /**
     * @override
     */
    handleLogin(options) {
      return this.$auth.loginWith('admin', options);
    },

    /**
     * @override
     */
    async completeSocialLogin() {
      log('[Page: admin/login.vue] completeSocialLogin');

      const response = await this.$socialEntry.completeAuthorization().catch(() => {});

      if (!response) {
        return false;
      }

      // If is a new user,
      // then make the form data and redirect user to register page.
      if (response.data.new_user || response.data.local_user_id == null) {
        log('[Page: admin/login.vue] completeSocialLogin > new_user');

        window.alert('The social account is not connected to any administrator user');

        resetParams();

        return false;
      }

      // In here, we complete social login by access token.
      else {
        log('[Page: admin/login.vue] completeSocialLogin.loginWithToken');

        const authResponse = await this.$socialEntry.loginWithToken(response.data.access_token);

        resetParams();

        await this.$auth.setUserToken(authResponse.data.access_token);
      }
    },
  },
};
</script>

<style scoped>
.sign-in-box::before,
.sign-in-box::after {
  content: '';
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  height: 20px;
  margin: 2px;

  background-image: repeating-linear-gradient(
    -45deg,
    var(--sign-in-box-bg, #fff),
    var(--sign-in-box-bg, #fff) 12px,
    var(--sign-in-box-decorated-color, #d2d6e0) 12px,
    var(--sign-in-box-decorated-color, #d2d6e0) 24px
  );
}

.sign-in-box::before {
  bottom: auto;
  height: 16px;
}

.sign-in-box::after {
  top: auto;
}
</style>
