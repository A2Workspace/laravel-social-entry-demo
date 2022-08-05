<template>
  <div class="login-page">
    <LoginSection headerText="Login As Administrator"></LoginSection>
  </div>
</template>

<script>
import BaseLoginPage from '../client/login';

export default {
  extends: BaseLoginPage,

  methods: {
    /**
     * @override
     */
    handleLogin(options) {
      return this.$auth.loginWith('admin', options);
    },
  },
};
</script>

<style scoped>
.sign-in-box::before,
.sign-in-box::after {
  content: '';
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  height: 20px;
  margin: 2px;

  background-image: repeating-linear-gradient(
    -45deg,
    var(--sign-in-box-bg, #fff),
    var(--sign-in-box-bg, #fff) 12px,
    var(--sign-in-box-decorated-color, #c3c8d5) 12px,
    var(--sign-in-box-decorated-color, #c3c8d5) 24px
  );
}

.sign-in-box::before {
  bottom: auto;
  height: 16px;
}

.sign-in-box::after {
  top: auto;
}
</style>
