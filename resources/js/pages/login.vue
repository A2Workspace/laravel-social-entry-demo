<template>
  <div class="login-page">
    <div class="sign-in-box__pos">
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
.sign-in-box__pos {
  padding: 15vh 0 0;
}
</style>
