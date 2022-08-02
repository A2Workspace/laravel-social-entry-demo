<template>
  <div class="login-page">
    <div class="sign-in-box-pos">
      <SignInBox />
    </div>
  </div>
</template>

<script>
import SignInBox from '../components/SignInBox';

export default {
  components: { SignInBox },
};
</script>

<style scoped>
.login-page {
  height: 100%;
}

.sign-in-box-pos {
  padding: 15vh 0 0;
}
</style>
