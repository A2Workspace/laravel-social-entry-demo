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
      console.log(`[Page: login.vue] Restore admin mode`);

      this.adminMode = true;
      this.$socialEntry.setStrategy('admin');
    }

    console.log(`[Page: login.vue] created`);
  },

  mounted() {
    this.watchRegisterModeValue();
  },

  updated() {
    this.watchRegisterModeValue();
  },
};
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
</style>
