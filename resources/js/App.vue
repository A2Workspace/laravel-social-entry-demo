<template>
  <div class="wrapper">
    <transition name="fade" mode="out-in" v-if="loaded">
      <ProfilePage v-if="isLoggedIn" />
      <LoginPage v-else />
    </transition>
  </div>
</template>

<script>
import Auth from './mixins/Auth';
import SocialEntry from './mixins/SocialEntry';
import LoginPage from './pages/login';
import ProfilePage from './pages/client/profile';

export default {
  components: {
    LoginPage,
    ProfilePage,
  },

  mixins: [Auth, SocialEntry],

  computed: {
    loaded() {
      return this.authState.loaded;
    },

    isLoggedIn() {
      return this.loggedIn;
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
  min-height: 100%;
}

.fade-enter-active,
.fade-leave-active {
  transition-property: opacity;
  transition-duration: 600ms;
}

.fade-enter-active {
  opacity: 0;
}

.fade-enter-to {
  opacity: 1;
}

.fade-leave-active {
  opacity: 1;
}

.fade-leave-to {
  opacity: 0;
}
</style>

