<template>
  <div class="wrapper">
    <!-- <ProfilePage v-if="loggedIn" /> -->
    <ProfilePage v-if="1" />
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

