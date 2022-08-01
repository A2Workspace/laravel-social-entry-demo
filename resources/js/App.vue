<template>
  <div class="wrapper">
    <ProfilePage v-if="loggedIn" />
    <LoginPage v-else />
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
};
</script>

<style>
html,
body {
  min-height: 100vh;
  margin: 0;
  background-color: #eceef3;
}

.wrapper {
  position: relative;
}
</style>

