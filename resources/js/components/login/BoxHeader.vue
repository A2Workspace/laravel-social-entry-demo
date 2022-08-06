<template>
  <div class="box-header" :class="className">
    <h2 class="box-header__text">
      <a :href="homePage" draggable="false">
        <slot>{{ text }}</slot>
      </a>
    </h2>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    text: {
      default: 'Social Entry',
    },
    small: {
      default: false,
    },
  },

  computed: {
    homePage() {
      return window.location.origin;
    },

    className() {
      if (this.small === '' || this.small !== false) {
        return {
          '--small': true,
        };
      }

      return {};
    },
  },
};
</script>

<style scoped>
.box-header {
  margin-bottom: 42px;
  user-select: none;
}

.box-header.--small .box-header__text {
  font-size: 1.3em;
}

.box-header__text {
  margin: 0;
  padding: 0;

  color: #3e3e3e;
  font-size: 1.7em;
  font-weight: bold;
  text-align: center;
}

.box-header__text a {
  color: inherit;
  text-decoration: none;
}

.box-header__text a:hover {
  opacity: 0.82;
}
</style>
