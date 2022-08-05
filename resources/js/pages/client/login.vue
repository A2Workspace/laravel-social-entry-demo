<template>
  <div class="login-page">
    <FlipCard ref="card" v-model="registerMode">
      <template #front>
        <LoginSection>
          <template #actions>
            <span></span>
            <a href="#" draggable="false" @click="registerMode = true">
              <span>Register</span>
              <i class="fas fa-external-link" />
            </a>
          </template>
        </LoginSection>
      </template>

      <template #back>
        <RegisterSection :options="registerOptions">
          <template #actions>
            <a href="#" draggable="false" @click="registerMode = false">
              <span>Sign In</span>
            </a>
          </template>
        </RegisterSection>
      </template>
    </FlipCard>
  </div>
</template>

<script>
import axios from 'axios';
import FlipCard from '../../components/login/FlipCard';
import LoginSection from '../../components/login/LoginSection';
import RegisterSection from '../../components/login/RegisterSection';
import { resetParams } from '../../mixins/SocialEntry';

export default {
  components: {
    FlipCard,
    LoginSection,
    RegisterSection,
  },

  inject: ['$auth', '$socialEntry'],

  data() {
    return {
      registerMode: false,
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

    async completeSocialLogin() {
      const response = await this.$socialEntry.completeAuthorization().catch(() => {});

      if (!response) {
        return false;
      }

      if (response.data.new_user || response.data.local_user_id == null) {
        this.registerMode = true;

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

    getSocialLoginRedirectUrl(provider) {
      return this.$socialEntry.authorize(provider).getTargetUrl();
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

<style>
.login-page {
  padding-top: 18.5vh;
}
</style>
