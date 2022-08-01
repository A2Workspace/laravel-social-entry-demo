<template>
  <div class="sign-in-box__inner">
    <div class="sign-in-box__contain">
      <SectionHeader></SectionHeader>

      <p class="sign-in-box__error-message" v-show="errorMessage">{{ errorMessage }}</p>

      <form class="sign-in-box__form" :class="{ '--shaking': errorMessage }" @submit.prevent="handleLogin">
        <SectionFormItem
          type="text"
          label="Username"
          ref="inputUsername"
          :disabled="isProcessing"
          v-model="form.username"
        />

        <SectionFormItem
          type="password"
          label="Password"
          autocomplete="new-password"
          ref="inputPassword"
          :disabled="isProcessing"
          v-model="form.password"
        />

        <SectionFormItem
          type="text"
          label="Nickname"
          ref="inputNickname"
          :disabled="isProcessing"
          v-model="form.nickname"
        />

        <SectionButton :processing="isProcessing">Create User</SectionButton>
      </form>
    </div>
  </div>
</template>

<script>
import SectionButton from './SectionButton';
import SectionFormItem from './SectionFormItem';
import SectionHeader from './SectionHeader';

export default {
  components: {
    SectionButton,
    SectionFormItem,
    SectionHeader,
  },

  inject: ['$auth'],

  props: {
    formData: {
      type: Object,
      default: () => ({}),
    },
  },

  data() {
    return {
      processing: false,
      errors: {},
      errorMessage: null,
      form: {
        username: '',
        nickname: '',
        password: '',
        ...this.formData,
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
