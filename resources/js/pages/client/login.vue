<template>
  <div class="login-page">
    <FlipCard ref="card" v-model="registerMode">
      <template #front>
        <LoginPane>
          <template #actions>
            <span></span>
            <a href="#" draggable="false" @click="registerMode = true">
              <span>Register</span>
              <i class="fas fa-external-link" />
            </a>
          </template>
        </LoginPane>
      </template>

      <template #back>
        <RegisterPane :options="registerOptions">
          <template #actions>
            <a href="#" draggable="false" @click="registerMode = false">
              <span>Sign In</span>
            </a>
          </template>
        </RegisterPane>
      </template>
    </FlipCard>
  </div>
</template>

<script>
import axios from 'axios';
import FlipCard from '@/components/login/FlipCard';
import LoginPane from '@/components/login/LoginPane';
import RegisterPane from '@/components/login/RegisterPane';

export default {
  components: {
    FlipCard,
    LoginPane,
    RegisterPane,
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
    // =========================================================================
    // = Login/Reigster
    // =========================================================================

    handleLogin(options) {
      resetParams();

      return this.$auth.loginWith('client', options);
    },

    handleRegister(formData) {
      return axios.post('/api/register', formData);
    },

    // =========================================================================
    // = Social Login
    // =========================================================================

    handleSocialLogin(provider) {
      this.$socialEntry.authorize(provider).redirect();
    },

    async completeSocialLogin() {
      log('[Page: client/login.vue] completeSocialLogin');

      const response = await this.$socialEntry.completeAuthorization().catch(() => {});

      if (!response) {
        return false;
      }

      // If is a new user,
      // then make the form data and redirect user to register page.
      if (response.data.new_user || response.data.local_user_id == null) {
        log('[Page: client/login.vue] completeSocialLogin.new_user');

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

      // In here, we complete social login by access token.
      else {
        log('[Page: client/login.vue] completeSocialLogin.loginWithToken');

        const authResponse = await this.$socialEntry.loginWithToken(response.data.access_token);

        resetParams();

        await this.$auth.setUserToken(authResponse.data.access_token);
      }
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

export function resetParams() {
  window.history.replaceState({}, window.document.title, window.location.href.split('?')[0]);
}

export function log(message) {
  console.log(`%c${message}`, 'background: #a9dfbf');
}
</script>

<style>
.login-page {
  padding-top: 18.5vh;
}
</style>
