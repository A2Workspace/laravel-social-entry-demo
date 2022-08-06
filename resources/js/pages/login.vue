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

    <footer>
      <p>Powered by</p>
      <strong
        ><a href="https://github.com/A2Workspace/laravel-social-entry">A2Workspace / Laravel-Social-Entry</a></strong
      >
    </footer>
  </div>
</template>

<script>
import PaneSwitch from '@/components/PaneSwitch';
import AdminLoginPage from './admin/login';
import ClientLoginPage from './client/login';

export default {
  components: {
    PaneSwitch,
    AdminLoginPage,
    ClientLoginPage,
  },

  inject: ['$socialEntry'],

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
          window.history.replaceState(null, null, '?admin');
        } else {
          window.history.replaceState(null, null, '?');
        }
      },
    },
  },

  created() {
    // Retrieve adminMode state from URL params.
    const parars = new URLSearchParams(window.location.search);

    if (parars.has('admin') && parars.get('admin') !== 'false') {
      log('[Page: login.vue] Restore admin mode');

      this.adminMode = true;
      this.$socialEntry.setStrategy('admin');
    }

    log('[Page: login.vue] created');
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

footer {
  display: block;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  margin: 2em 0;
  color: #888;
  font-size: 14px;
  text-align: center;
  line-height: 1.4;
}

footer p {
  margin: 0;
}

footer strong {
  font-weight: normal;
  color: #555;
}

footer a {
  color: inherit;
}
</style>
