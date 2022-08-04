<template>
  <div class="sign-in-box__inner">
    <div class="sign-in-box__top-actions">
      <a href="#" draggable="false" @click="toLoginPage">
        <span>Sign In</span>
      </a>
    </div>

    <div class="sign-in-box__contain">
      <SectionHeader small>Social Entry</SectionHeader>

      <SocialConnectingHeader :service="socialProvider" :userAvatar="socialAvatar" v-show="socialProvider" />

      <p class="sign-in-box__error-message" v-show="errorMessage">{{ errorMessage }}</p>

      <form
        class="sign-in-box__form"
        ref="form"
        :class="{ '--shaking': errorMessage }"
        @submit.prevent="handleRegister"
      >
        <SectionFormItem
          type="text"
          :label="socialIdentifierLabel"
          disabled
          :value="socialIdentifier"
          v-if="socialIdentifier"
        />

        <SectionFormItem
          type="text"
          label="Username"
          name="username"
          :disabled="isProcessing"
          :error="errors.username"
          v-model="form.username"
        />

        <SectionFormItem
          type="password"
          label="Password"
          name="password"
          autocomplete="new-password"
          :disabled="isProcessing"
          :error="errors.password"
          v-model="form.password"
        />

        <SectionFormItem
          type="text"
          label="Nickname"
          name="nickname"
          :disabled="isProcessing"
          :error="errors.nickname"
          v-model="form.nickname"
        />

        <SectionButton :processing="isProcessing">Create Account</SectionButton>
      </form>
    </div>
  </div>
</template>

<script>
import SectionButton from './pures/SectionButton';
import SectionFormItem from './pures/SectionFormItem';
import SectionHeader from './pures/SectionHeader';
import SocialConnectingHeader from './pures/SocialConnectingHeader';

export default {
  components: {
    SectionButton,
    SectionFormItem,
    SectionHeader,
    SocialConnectingHeader,
  },

  inject: {
    doHandleRegister: 'handleRegister',
    toLoginPage: 'toLoginPage',
  },

  props: {
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
};
</script>

