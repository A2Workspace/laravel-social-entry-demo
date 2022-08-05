<template>
  <div class="login-page-wrapper">
    <div class="pane-switch-pos">
      <PaneSwitch inactiveText="Client" activeText="Admin" v-model="adminMode" />
    </div>

    <transition name="traverse">
      <AdminLoginPage v-if="adminMode" />
      <ClientLoginPage v-else />
    </transition>
  </div>
</template>

<script>
import PaneSwitch from '../components/PaneSwitch';
import AdminLoginPage from './admin/login';
import ClientLoginPage from './client/login';

export default {
  components: {
    PaneSwitch,
    AdminLoginPage,
    ClientLoginPage,
  },

  data() {
    return {
      adminMode: false,
    };
  },

  computed: {
    isAdminMode() {
      return this.adminMode;
    },
  },
};
</script>

<style>
.login-page-wrapper {
  overflow: hidden;
  position: relative;
  height: 100%;
}

.pane-switch-pos {
  position: absolute;
  display: grid;
  place-content: center;
  width: 100%;
  top: 10vh;
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
  transform: translateX(300px) scale(1.05);
}

.traverse-enter-to {
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
