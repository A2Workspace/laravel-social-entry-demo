<template>
  <div class="sign-in-box__inner">
    <div class="sign-in-box__contain">
      <SignInBoxHeader></SignInBoxHeader>

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

        <input
          class="sign-in-box__input"
          type="text"
          placeholder="Nickname"
          ref="inputNickname"
          :disabled="isProcessing"
          v-model="form.nickname"
        />

        <SubmitButton :processing="isProcessing">Create User</SubmitButton>
      </form>
    </div>
  </div>
</template>

<script>
import SignInBoxHeader from './SignInBoxHeader';
import SubmitButton from './SubmitButton';

export default {
  components: {
    SignInBoxHeader,
    SubmitButton,
  },

  inject: ['$auth'],

  data() {
    return {
      processing: false,
      errors: {},
      errorMessage: null,
      form: {
        username: '',
        nickname: '',
        password: '',
      },
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
