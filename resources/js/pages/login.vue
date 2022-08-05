<template>
  <div class="login-page-wrapper">
    <div class="pane-switch-pos" :class="{ '--hide': registerMode }">
      <PaneSwitch inactiveText="Client" activeText="Admin" v-model="inputAdminMode" />
    </div>

    <transition name="traverse">
      <AdminLoginPage v-if="adminMode" />
      <ClientLoginPage v-else :registerMode="registerMode" ref="clientLogin" />
    </transition>
  </div>
</template>

<script>
import PaneSwitch from '../components/PaneSwitch';
import { resetParams } from '../mixins/SocialEntry';
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
      registerMode: false,
    };
  },

  computed: {
    inputAdminMode: {
      get() {
        return this.adminMode;
      },
      set(v) {
        resetParams();
        this.adminMode = v;
      },
    },
  },

  mounted() {
    this.$watch(
      () => this.$refs.clientLogin.registerMode,
      (value) => {
        this.registerMode = value;
      }
    );
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

  transition-property: opacity top;
  transition-duration: 300ms;
}

.pane-switch-pos.--hide {
  opacity: 0;
  top: 5vh;
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
