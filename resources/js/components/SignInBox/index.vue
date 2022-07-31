<template>
  <div class="sign-in-box">
    <div class="sign-in-box__contain">
      <h2 class="sign-in-box__header">Social Entry</h2>

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

        <button class="sign-in-box__button" :disabled="isProcessing">
          <span v-show="!isProcessing">Sign In</span>
          <span class="sign-in-box__button-icon" v-show="isProcessing">
            <i class="far fa-spinner-third" />
          </span>
        </button>
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
import SocialEntry from './SocialEntry';

export default {
  name: 'SignInBox',
  components: { SocialEntry },

  inject: ['$auth'],

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

      return this.$auth.loginWith('user', certificate)
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

      if (response.status === 401) {
        setTimeout(() => this.$refs.inputPassword.select());
      }

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
  },
};
</script>

<style>
.sign-in-box {
  display: flex;
  flex-flow: column nowrap;
  justify-content: space-between;
  align-items: stretch;
  position: relative;

  box-sizing: border-box;
  width: 400px;
  min-height: 520px;
  margin: 30px auto;
  padding: 40px 30px 30px;
  background: #fff;
  border: 1px solid #efefef;
  border-radius: 2px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.sign-in-box__header {
  margin: 0;
  margin-bottom: 42px;
  padding: 0;
  color: #3e3e3e;
  font-size: 1.7em;
  text-align: center;
}

.sign-in-box__form.--shaking {
  animation: shaking 0.5s;
}

@keyframes shaking {
  10% {
    transform: translate(-8%, 0);
  }
  25% {
    transform: translate(8%, 0);
  }
  40%,
  70% {
    transform: translate(-2%, 0);
  }
  55%,
  85% {
    transform: translate(2%, 0);
  }
}

.sign-in-box__input {
  box-sizing: border-box;
  width: 100%;
  height: 44px;
  margin-bottom: 18px;
  padding: 8px 14px 7px;
  background-color: #fff;
  border: 1px solid #dcdfe6;
  border-radius: 2px;
  outline: 0;
  font-size: 14px;
}

.sign-in-box__input:focus {
  border: 1px solid #7984a0;
}

.sign-in-box__input:disabled {
  color: #aaa;
  cursor: progress;
}

.sign-in-box__button {
  box-sizing: border-box;
  width: 100%;
  height: 44px;
  margin-top: 2px;
  background-color: #3f475a;
  border: 0;
  border-radius: 2px;
  color: #fafafa;
  font-size: 15px;
  letter-spacing: 0.2px;
  text-align: center;
  outline: 0;
  cursor: pointer;
  user-select: none;
}

.sign-in-box__button:hover {
  background-color: #545f78;
  transition: background-color 200ms ease-out;
}

.sign-in-box__button:active {
  box-shadow: 0 4px 3px rgba(0, 0, 0, 0.2) inset;
  padding-top: 3px;
}

.sign-in-box__button:disabled {
  background-color: #3f475a !important;
  color: #ccc;
  cursor: progress;
}

.sign-in-box__button-icon {
  font-size: 1.3em;
}

.sign-in-box__button-icon i {
  animation: spin 900ms linear infinite;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}

.sign-in-box__error-message {
  margin: 0;
  margin-bottom: 1em;
  color: var(--color-error, #ec4646);
  font-size: 15px;
  text-align: center;
  line-height: 1;
}

.sign-in-box__social-entry {
  margin-bottom: 30px;
}

.sign-in-box__social-entry-text {
  margin: 0;
  margin-bottom: 18px;
  color: #777;
  font-size: 14px;
  text-align: center;
}

.sign-in-box__social-entry-links {
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  align-items: center;
  gap: 10px;
}
</style>
