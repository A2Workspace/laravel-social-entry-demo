<template>
  <div class="wrapper">
    <ProfilePage v-if="status === 'logged_in'" />
    <LoginPage v-if="status === 'default'" />
  </div>
</template>

<script>
import Auth from './mixins/Auth';
import SocialEntry from './mixins/SocialEntry';
import LoginPage from './pages/login';
import ProfilePage from './pages/profile';

export default {
  mixins: [Auth, SocialEntry],
  components: { LoginPage, ProfilePage },

  computed: {
    status() {
      if (! this.authState.loaded) {
        return 'processing';
      }

      if (this.loggedIn) {
        return 'logged_in';
      }

      return 'default';
    },
  },
};
</script>

<style>
html,
body {
  height: 100%;
  min-height: 100vh;
  margin: 0;
  background-color: #eceef3;
}

.wrapper {
  position: relative;
  height: 100%;
}
</style>

