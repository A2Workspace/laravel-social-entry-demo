<template>
  <div class="wrapper">
    <div class="pane-switch-pos">
      <PaneSwitch inactiveText="Client" activeText="Admin" v-model="adminMode" />
    </div>

    <div class="transition-wrapper">
      <transition name="traverse">
        <AdminLoginPage v-if="status === 'admin_login'" />
        <ProfilePage v-if="status === 'logged_in'" />
        <LoginPage v-if="status === 'default'" />
      </transition>
    </div>
  </div>
</template>

<script>
import Auth from './mixins/Auth';
import SocialEntry from './mixins/SocialEntry';
import PaneSwitch from './components/PaneSwitch';
import LoginPage from './pages/login';
import ProfilePage from './pages/profile';
import AdminLoginPage from './pages/admin/login';

export default {
  components: {
    PaneSwitch,
    LoginPage,
    ProfilePage,
    AdminLoginPage,
  },

  mixins: [Auth, SocialEntry],

  data() {
    return {
      adminMode: false,
    };
  },

  computed: {
    status() {
      if (!this.authState.loaded) {
        return 'processing';
      }

      if (this.adminMode) {
        return 'admin_login';
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
  overflow: hidden;
  min-height: 100%;
}

.pane-switch-pos {
  display: grid;
  place-content: center;
  width: 100%;
  padding-top: 10vh;
  padding-bottom: 57px;
}

.transition-wrapper {
  position: relative;
}

.traverse-enter-active,
.traverse-leave-active {
  position: absolute;
  top: 0;
  width: 100%;
  transition-property: opacity transform;
  transition-duration: 600ms;
}

.traverse-enter-active {
  /* opacity: 0; */
  transform: translateX(300px) scale(1.05);
  box-shadow: 0 50px );
}

.traverse-enter-to {
  /* opacity: 1; */
  transform: translateX(0);
}

.traverse-leave-active {
  opacity: 1;
  transform: translateX(0);
}

.traverse-leave-to {
  opacity: 0;
  transform: translateX(-300px);
}
</style>

