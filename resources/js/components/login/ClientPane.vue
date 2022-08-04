<template>
  <SignInBox>
    <RegisterSection v-if="'sign_on' === status" :options="registerOptions">
      <template #actions>
        <a href="#" draggable="false" @click="status = 'sign_in'">
          <span>Sign In</span>
        </a>
      </template>
    </RegisterSection>

    <LoginSection v-else>
      <template #actions>
        <span></span>
        <a href="#" draggable="false" @click="status = 'sign_on'">
          <span>Register</span>
          <i class="fas fa-external-link" />
        </a>
      </template>
    </LoginSection>
  </SignInBox>
</template>

<script>
import axios from 'axios';
import SignInBox from './pures/SignInBox';
import LoginSection from './pures/LoginSection';
import RegisterSection from './pures/RegisterSection';
import { resetParams } from '../../mixins/SocialEntry';

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
      handleLogin: this.handleLogin,
      handleRegister: this.handleRegister,
      handleSocialLogin: this.handleSocialLogin,
      getSocialLoginRedirectUrl: this.getSocialLoginRedirectUrl,
    };
  },

  methods: {
    handleLogin(strategy, options) {
      resetParams();

      return this.$auth.loginWith(strategy, options);
    },

    handleRegister(formData) {
      return axios.post('/api/register', formData);
    },

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

      resetParams();

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
