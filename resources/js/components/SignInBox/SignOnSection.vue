<template>
  <div class="sign-in-box__inner">
    <div class="sign-in-box__top-actions">
      <a href="#" draggable="false" @click="toLoginPage">
        <span>Sign On</span>
      </a>
    </div>

    <div class="sign-in-box__contain">
      <SectionHeader></SectionHeader>

      <p class="sign-in-box__error-message" v-show="errorMessage">{{ errorMessage }}</p>

      <form class="sign-in-box__form" ref="form" :class="{ '--shaking': errorMessage }" @submit.prevent="handleSignOn">
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
import axios from 'axios';
import SectionButton from './SectionButton';
import SectionFormItem from './SectionFormItem';
import SectionHeader from './SectionHeader';

export default {
  components: {
    SectionButton,
    SectionFormItem,
    SectionHeader,
  },

  inject: ['toLoginPage'],

  props: {
    formData: {
      type: Object,
      default: () => ({}),
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
        ...this.formData,
      },
    };
  },

  methods: {
    handleSignOn() {
      this.resetErrors();

      if (!this.validateFormInputs()) {
        return;
      }

      this.doHandleSignOn();
    },

    resetErrors() {
      this.errors = {};
      this.errorMessage = null;
    },

    validateFormInputs() {
      if (this.form.username == '') {
        setTimeout(() => {
          this.$refs.form.querySelector('[name="username"]').focus();
        });

        return false;
      }

      if (this.form.password == '') {
        setTimeout(() => {
          this.$refs.form.querySelector('[name="password"]').focus();
        });

        return false;
      }

      return true;
    },

    doHandleSignOn() {
      if (this.processing) {
        return;
      }

      let request = axios.post('/api/register', this.form);

      request = request.then((response) => {
        window.alert(`New user created`);
        window.location.reload('');
      });

      request = request.catch((error) => {
        if (!error.isAxiosError) {
          throw error;
        }

        const { response } = error;

        this.errorMessage = response.data.message || `Status Code: ${response.status}`;

        if (response.status === 422 && typeof response.data.errors === 'object') {
          this.errors = response.data.errors;
        }
      });

      request = request.finally(() => {
        this.processing = null;
      });

      this.processing = request;

      return request;
    },
  },

  computed: {
    isProcessing() {
      return Boolean(this.processing);
    },
  },
};
</script>
