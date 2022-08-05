<template>
  <SignInBox>
    <div class="sign-in-box__top-actions">
      <slot name="actions"></slot>
    </div>

    <div class="sign-in-box__contain">
      <BoxHeader small>{{ headerText }}</BoxHeader>

      <SocialConnectingHeader :service="socialProvider" :userAvatar="socialAvatar" v-show="socialProvider" />

      <p class="sign-in-box__error-message" v-show="errorMessage">{{ errorMessage }}</p>

      <form
        class="sign-in-box__form"
        ref="form"
        :class="{ '--shaking': errorMessage }"
        @submit.prevent="handleRegister"
      >
        <BoxFormItem
          type="text"
          :label="socialIdentifierLabel"
          disabled
          :value="socialIdentifier"
          v-if="socialIdentifier"
        />

        <BoxFormItem
          type="text"
          label="Username"
          name="username"
          :disabled="isProcessing"
          :error="errors.username"
          v-model="form.username"
        />

        <BoxFormItem
          type="password"
          label="Password"
          name="password"
          autocomplete="new-password"
          :disabled="isProcessing"
          :error="errors.password"
          v-model="form.password"
        />

        <BoxFormItem
          type="text"
          label="Nickname"
          name="nickname"
          :disabled="isProcessing"
          :error="errors.nickname"
          v-model="form.nickname"
        />

        <BoxButton :processing="isProcessing">{{ registerButtonText }}</BoxButton>
      </form>
    </div>
  </SignInBox>
</template>

<script>
import BoxButton from './BoxButton';
import BoxFormItem from './BoxFormItem';
import BoxHeader from './BoxHeader';
import SignInBox from './SignInBox';
import SocialConnectingHeader from './SocialConnectingHeader';

export default {
  components: {
    BoxButton,
    BoxFormItem,
    BoxHeader,
    SignInBox,
    SocialConnectingHeader,
  },

  props: {
    headerText: {
      default: 'Social Entry',
    },
    registerButtonText: {
      default: 'Create Account',
    },
    options: {
      type: Object,
      default: () => ({
        form: {},
        socialProvider: null,
        socialAvatar: null,
        socialIdentifier: null,
        accessToken: null,
      }),
    },
  },

  inject: {
    doHandleRegister: 'handleRegister',
  },

  data() {
    return {
      processing: null,
      errors: {},
      errorMessage: null,
      form: {
        username: '',
        nickname: '',
        password: '',
        ...this.options.form,
      },
    };
  },

  methods: {
    handleRegister() {
      this.resetErrors();

      if (!this.validateFormInputs()) {
        return;
      }

      if (this.processing) {
        return;
      }

      const formData = { ...this.form };

      if (this.accessToken) {
        formData['access_token'] = this.accessToken;
      }

      this.processing = this.doHandleRegister(formData)
        // Handling successfully.
        .then((response) => {
          window.alert(`New account created`);
          window.location.replace('/');
        })
        // Handling error message.
        .catch((error) => {
          if (!error.isAxiosError) {
            throw error;
          }

          const { response } = error;

          this.errorMessage = response.data.message || `Status Code: ${response.status}`;

          if (response.status === 422 && typeof response.data.errors === 'object') {
            this.errors = response.data.errors;
          }
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
      return Boolean(this.processing);
    },

    accessToken() {
      return this.options.accessToken;
    },

    socialProvider() {
      return this.options.socialProvider;
    },

    socialAvatar() {
      return this.options.socialAvatar;
    },

    socialIdentifier() {
      return this.options.socialIdentifier;
    },

    socialIdentifierLabel() {
      let provider = this.socialProvider;

      if (provider) {
        return provider.charAt(0).toUpperCase() + provider.substr(1) + ' ID';
      }
    },
  },

  watch: {
    'options.form'(newestFormData) {
      if (typeof newestFormData !== 'object') {
        return;
      }

      if (this.form.username == '') {
        this.form.username = newestFormData.username;
      }

      if (this.form.nickname == '') {
        this.form.nickname = newestFormData.nickname;
      }
    },
  },
};
</script>

