<template>
  <SignInBox>
    <div class="sign-in-box__top-actions">
      <slot name="actions"></slot>
    </div>

    <div class="sign-in-box__contain">
      <BoxHeader>{{ headerText }}</BoxHeader>

      <p class="sign-in-box__error-message" v-show="errorMessage">{{ errorMessage }}</p>

      <form class="sign-in-box__form" ref="form" :class="{ '--shaking': errorMessage }" @submit.prevent="handleLogin">
        <BoxFormItem
          type="text"
          label="Username"
          name="username"
          :disabled="isProcessing"
          v-model="form.username"
        />

        <BoxFormItem
          type="password"
          label="Password"
          name="password"
          autocomplete="new-password"
          :disabled="isProcessing"
          v-model="form.password"
        />

        <BoxButton :processing="isProcessing">{{ loginButtonText }}</BoxButton>
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
  </SignInBox>
</template>

<script>
import BoxButton from './BoxButton';
import BoxFormItem from './BoxFormItem';
import BoxHeader from './BoxHeader';
import SignInBox from './SignInBox';
import SocialEntry from './SocialEntry';

export default {
  components: {
    BoxButton,
    BoxFormItem,
    BoxHeader,
    SignInBox,
    SocialEntry,
  },

  props: {
    headerText: {
      default: 'Social Entry',
    },
    loginButtonText: {
      default: 'Sign In',
    },
  },

  inject: {
    doHandleLogin: 'handleLogin',
  },

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

      this.processing = this.doHandleLogin(certificate)
        // Handling error message.
        .catch((error) => {
          if (!error.isAxiosError) {
            throw error;
          }

          this.errorMessage = error.response.data?.error || `Status Code: ${error.response.status}`;
        })
        // Unlock
        .finally(() => {
          this.processing = null;
        });
    },

    resetErrors() {
      this.errors = {};
      this.errorMessage = null;
    },

    validateFormInputs() {
      if (!this.form.username) {
        setTimeout(() => {
          this.$refs.form.querySelector('[name="username"]').focus();
        });

        return false;
      }

      if (!this.form.password) {
        setTimeout(() => {
          this.$refs.form.querySelector('[name="password"]').focus();
        });

        return false;
      }

      return true;
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

<style scoped>
.sign-in-box__form {
  min-height: 250px;
}
</style>

