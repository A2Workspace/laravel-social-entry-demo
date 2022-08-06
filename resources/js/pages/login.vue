<template>
  <div class="login-page-wrap">
    <div class="pane-switch-pos" :class="{ '--hide': registerMode }">
      <PaneSwitch inactiveText="Client" activeText="Admin" v-model="inputAdminMode" />
    </div>

    <transition name="traverse">
      <template v-if="adminMode">
        <AdminLoginPage />
      </template>
      <template v-else>
        <ClientLoginPage :registerMode="registerMode" ref="clientLogin" />
      </template>
    </transition>

    <AppFooter />
  </div>
</template>

<script>
import PaneSwitch from '@/components/PaneSwitch';
import AppFooter from '@/components/AppFooter';
import AdminLoginPage from './admin/login';
import ClientLoginPage from './client/login';

export default {
  components: {
    PaneSwitch,
    AppFooter,
    AdminLoginPage,
    ClientLoginPage,
  },

  inject: ['$auth', '$socialEntry'],

  data() {
    return {
      adminMode: false,
      registerMode: false,
    };
  },

  methods: {
    watchRegisterModeValue() {
      const ref = this.$refs.clientLogin;

      if (!ref) {
        return;
      }

      if (this._registerModeValueRef && this._registerModeValueRef === ref) {
        return;
      }

      this._registerModeValueRef = ref;

      if (this._registerModeValueWatcher) {
        this._registerModeValueWatcher();
      }

      this._registerModeValueWatcher = this.$watch(
        () => ref.registerMode,
        (value) => {
          this.registerMode = value;
        }
      );
    },
  },

  computed: {
    inputAdminMode: {
      get() {
        return this.adminMode;
      },
      set(v) {
        this.adminMode = v;

        if (this.adminMode) {
          log('[Page: login.vue] Switch to admin user login pane');
          window.history.replaceState(null, null, '?admin');
        } else {
          log('[Page: login.vue] Switch to default login pane');
          window.history.replaceState(null, null, '?');
        }
      },
    },
  },

  created() {
    if (this.$auth.getStrategy() === 'admin') {
      log('[Page: login.vue] Sync admin mode status');

      this.adminMode = true;
    }

    const parars = new URLSearchParams(window.location.search);
    if (parars.has('admin') && parars.get('admin') !== 'false') {
      log('[Page: login.vue] Restore admin mode');

      this.adminMode = true;
      this.$auth.setStrategy('admin');
      this.$socialEntry.setStrategy('admin');
    } else log('[Page: login.vue] created');
  },

  mounted() {
    this.watchRegisterModeValue();
  },

  updated() {
    this.watchRegisterModeValue();
  },
};

function log(message) {
  console.log(`%c${message}`, 'background: #d4efdf');
}
</script>

<style>
.login-page-wrap {
  /* position: relative; */
  box-sizing: border-box;
}

.pane-switch-pos {
  position: absolute;
  display: grid;
  place-content: center;
  width: 100%;
  top: 10vh;

  transition-property: opacity, top;
  transition-duration: 300ms;
}

.pane-switch-pos.--hide {
  opacity: 0;
  top: 5vh;
}

@media screen and (max-height: 768px) {
  .pane-switch-pos {
    top: 30px;
  }

  .pane-switch-pos.--hide {
    top: 0;
  }
}

.transition-wrapper {
  position: relative;
}

.traverse-enter-active,
.traverse-leave-active {
  position: absolute;
  top: 0;
  width: 100%;
  transition-property: opacity, transform;
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
