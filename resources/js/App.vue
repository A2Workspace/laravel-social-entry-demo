<template>
  <div class="wrapper">
    <transition name="fade" mode="out-in" v-if="loaded">
      <AdminProfilePage v-if="'admin.profile' === status" />
      <ClientProfilePage v-if="'client.profile' === status" />
      <MixLoginPage v-if="'login' === status" />
    </transition>
  </div>
</template>

<script>
import Auth from './mixins/Auth';
import SocialEntry from './mixins/SocialEntry';
import MixLoginPage from './pages/login';
import AdminProfilePage from './pages/admin/profile';
import ClientProfilePage from './pages/client/profile';

export default {
  components: {
    MixLoginPage,
    AdminProfilePage,
    ClientProfilePage,
  },

  mixins: [Auth, SocialEntry],

  computed: {
    loaded() {
      return this.authState.loaded;
    },

    status() {
      if (this.loggedIn) {
        if (this.authState.strategy === 'admin') {
          return 'admin.profile';
        } else {
          return 'client.profile';
        }
      }

      return 'login';
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
  overflow: hidden;
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

