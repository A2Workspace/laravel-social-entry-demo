<template>
  <div class="sign-in-box__inner">
    <div class="sign-in-box__top-actions">
      <span></span>
      <a href="#" draggable="false" @click="toRegisterPage">
        <span>Register</span>
        <i class="fas fa-external-link" />
      </a>
    </div>

    <div class="sign-in-box__contain">
      <SectionHeader></SectionHeader>

      <p class="sign-in-box__error-message" v-show="errorMessage">{{ errorMessage }}</p>

      <form class="sign-in-box__form" :class="{ '--shaking': errorMessage }" @submit.prevent="handleLogin">
        <input
          class="sign-in-box__input"
          type="text"
          placeholder="Username"
          ref="inputUsername"
          :disabled="isProcessing"
          v-model="form.username"
        />

        <input
          class="sign-in-box__input"
          type="password"
          placeholder="Password"
          autocomplete="new-password"
          ref="inputPassword"
          :disabled="isProcessing"
          v-model="form.password"
        />

        <SectionButton :processing="isProcessing">Sign In</SectionButton>
      </form>
    </div>

    <div class="sign-in-box__contain">
      <div class="sign-in-box__social-entry">
        <p class="sign-in-box__social-entry-text">or continue with</p>
        <div class="sign-in-box__social-entry-links">
          <SocialEntry provider="github" />
          <SocialEntry provider="google" />
          <SocialEntry provider="facebook" />
          <SocialEntry provider="line" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SectionButton from './SectionButton';
import SectionFormItem from './SectionFormItem';
import SectionHeader from './SectionHeader';
import SocialEntry from './SocialEntry';

export default {
  components: {
    SectionButton,
    SectionFormItem,
    SectionHeader,
    SocialEntry,
  },

  inject: ['$auth', 'toRegisterPage'],

  data() {
    return {
      processing: false,
      form: {
        username: '',
        password: '',
      },
      errors: {},
      errorMessage: null,
    };
  },

  methods: {
    handleLogin() {
      this.resetErrors();

      if (!this.validateFormInputs()) {
        return;
      }

      if (this.processing) {
        return;
      }

      this.processing = true;

      const { username, password } = this.form;
      const certificate = { username, password };

      return this.$auth
        .loginWith('user', certificate)
        .catch((error) => {
          this.handleLoginError(error);
        })
        .finally(() => {
          this.processing = false;
        });
    },

    validateFormInputs() {
      if (this.form.username == '') {
        setTimeout(() => this.$refs.inputUsername.focus());
        return false;
      }

      if (this.form.password == '') {
        setTimeout(() => this.$refs.inputPassword.focus());
        return false;
      }

      return true;
    },

    handleLoginError(error) {
      if (!error.isAxiosError) {
        throw error;
      }

      const { response } = error;

      this.errorMessage = response.data.error;
    },

    resetErrors() {
      this.errors = {};
      this.errorMessage = null;
    },
  },

  computed: {
    isProcessing() {
      return this.processing;
    },

    homePage() {
      return window.location.origin;
    },
  },
};
</script>
