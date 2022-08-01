<template>
  <div class="sign-in-box-header">
    <h2 class="sign-in-box-header__text">
      <a :href="homePage" draggable="false">
        <slot>{{ text }}</slot>
      </a>
    </h2>
  </div>
</template>

<script>
export default {
  props: {
    text: {
      default: 'Social Entry',
    },
  },

  computed: {
    homePage() {
      return window.location.origin;
    },
  },
};
</script>

<style scoped>
.sign-in-box-header {
  margin-bottom: 42px;
  user-select: none;
}

.sign-in-box-header__text {
  margin: 0;
  padding: 0;

  color: #3e3e3e;
  font-size: 1.7em;
  font-weight: bold;
  text-align: center;
}

.sign-in-box-header__text a {
  color: inherit;
  text-decoration: none;
}

.sign-in-box-header__text a:hover {
  opacity: 0.82;
}
</style>
