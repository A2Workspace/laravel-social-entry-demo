<template>
  <div class="login-page">
    <div class="sign-in-box-pos">
      <SignInBox>
        <RegisterSection v-if="'sign_on' === status" :options="registerOptions" />
        <LoginSection v-else />
      </SignInBox>
    </div>
  </div>
</template>

<script>
import SignInBox from '../components/login/SignInBox';
import LoginSection from '../components/login/LoginSection';
import RegisterSection from '../components/login/RegisterSection';
import { resetParams } from '../mixins/SocialEntry';

export default {
  components: {
    SignInBox,
    LoginSection,
    RegisterSection,
  },

  inject: ['$auth', '$socialEntry'],

  data() {
    return {
      status: 'sign_in',
      defaultFormData: null,
      registerOptions: {},
    };
  },

  provide() {
    return {
      handleSocialLogin: this.handleSocialLogin,
      getSocialLoginRedirectUrl: this.getSocialLoginRedirectUrl,
      toLoginPage: () => (this.status = 'sign_in'),
      toRegisterPage: () => (this.status = 'sign_on'),
    };
  },

  methods: {
    handleSocialLogin(provider) {
      this.$socialEntry.authorize(provider).redirect();
    },

    getSocialLoginRedirectUrl(provider) {
      return this.$socialEntry.authorize(provider).getTargetUrl();
    },

    async completeSocialLogin() {
      const response = await this.$socialEntry.completeAuthorization().catch(() => {});

      if (!response) {
        return false;
      }

      if (response.data.new_user || response.data.local_user_id == null) {
        this.status = 'sign_on';

        this.registerOptions = {
          form: {
            username: resolveUsername(response.data.social_email),
            nickname: response.data.social_name,
          },
          socialProvider: response.data.provider,
          socialAvatar: response.data.social_avatar,
          socialIdentifier: response.data.identifier,
          accessToken: response.data.access_token,
        };

        return true;
      }

      const authResponse = await this.$socialEntry.loginWithToken(response.data.access_token);

      await this.$auth.setUserToken(authResponse.data.access_token);
    },
  },

  async mounted() {
    await this.completeSocialLogin();
  },
};

function resolveUsername(email) {
  if (!email) {
    return '';
  }

  return email.slice(0, email.indexOf('@'));
}
</script>

<style scoped>
.login-page {
  height: 100%;
}

.sign-in-box-pos {
  padding: 15vh 0 0;
}
</style>
