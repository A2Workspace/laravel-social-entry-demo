<template>
  <div class="wrapper">
    <div class="pane-switch-pos">
      <PaneSwitch inactiveText="Client" activeText="Admin" v-model="adminMode" />
    </div>

    <AdminLoginPage v-if="status === 'admin_login'" />
    <ProfilePage v-if="status === 'logged_in'" />
    <LoginPage v-if="status === 'default'" />
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
  height: 100%;
}

.pane-switch-pos {
  display: grid;
  place-content: center;
  width: 100%;
  margin-top: 10vh;
  margin-bottom: 57px;
}
</style>

