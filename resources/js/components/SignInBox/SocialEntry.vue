<template>
  <a class="social-entry" :href="targetUrl" draggable="false" @click="handleAuthorization">
    <i :class="iconClassName"></i>
  </a>
</template>

<script>
export default {
  inject: ['$socialEntry'],

  props: {
    provider: {
      require: true,
    },
  },

  methods: {
    handleAuthorization() {
      this.$socialEntry.authorize(this.provider).redirect();
    },
  },

  computed: {
    targetUrl() {
      this.$socialEntry.authorize(this.provider).getTargetUrl();
    },

    iconClassName() {
      return `fab fa-${this.provider}`;
    },
  },
};
</script>

<style scoped>
.social-entry {
  display: inline-block;
  opacity: 0.7;

  box-sizing: border-box;
  width: 54px;
  height: 54px;
  line-height: 54px;
  border: 1px solid #ccc;
  border-radius: 2px;

  color: #666;
  text-align: center;
  vertical-align: baseline;
  font-size: 26px;

  cursor: pointer;
  user-select: none;
}

.social-entry:hover {
  opacity: 1;
  transition: opacity 150ms ease-out;
}

.social-entry:active {
  box-shadow: 0 3px 3px rgba(0, 0, 0, 0.15) inset;
  padding-top: 2px;
}
</style>
