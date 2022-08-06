<template>
  <div class="pane-switch" :class="{ '--actived': status }" @click="toggle">
    <div class="pane-switch__inner">
      <span class="pane-switch__inactive-text">{{ inactiveText }}</span>
      <span class="pane-switch__active-text">{{ activeText }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    inactiveText: {
      default: 'Inactive',
    },
    activeText: {
      default: 'Active',
    },
  },

  data() {
    return {
      innerStatus: false,
    };
  },

  methods: {
    toggle() {
      this.status = !this.status;
    },
  },

  computed: {
    status: {
      get() {
        if (typeof this.value !== 'undefined' && this.value !== null) {
          return this.value;
        }

        return this.innerStatus;
      },
      set(v) {
        if (v !== this.value) {
          this.$emit('input', v);
        }

        this.innerStatus = v;
      },
    },
  },
};
</script>

<style>
.pane-switch {
  position: relative;
  box-sizing: border-box;
  z-index: 800;
  width: 200px;
  height: 35px;
  line-height: 35px;
  cursor: pointer;
  user-select: none;
}

.pane-switch::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: -2;
  background: var(--pane-switch-color, #3f475a);
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.35) inset;
  border-radius: 4px;
}

.pane-switch::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: -1;
  width: calc(50% - 4px);
  margin: 2px;
  background-color: var(--bg-color, #eceef3);
  border-radius: 3px;
  transition: left 300ms ease-in-out;
}

.pane-switch.--actived::after {
  left: 50%;
}

.pane-switch__inner {
  display: flex;
  flex-flow: row nowrap;
  justify-content: space-between;
  align-items: stretch;
  color: #fff;
  font-size: 14px;
  font-weight: normal;
  text-align: center;
  letter-spacing: 0.4px;
  mix-blend-mode: difference;
}

.pane-switch__inactive-text,
.pane-switch__active-text {
  flex: 1 0 50%;
  display: inline-block;
  box-sizing: border-box;
  padding: 0 20px 0;
}
</style>
