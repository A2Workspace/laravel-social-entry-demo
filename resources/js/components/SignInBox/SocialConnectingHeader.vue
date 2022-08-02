<template>
  <div class="social-connecting-header">
    <div class="social-connecting-header__icon">
      <i :class="iconClassName">
        <div class="social-connecting-header__connecting-icon">
          <i class="fas fa-exchange-alt"></i>
        </div>
      </i>
    </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    service: {
      default: null,
      // require: true,
    },
  },

  computed: {
    style() {
      return {
        width: `${this.iconSize}px`,
        height: `${this.iconSize}px`,
        background: this.iconColor,
        color: this.iconColor,
        fontSize: `${this.iconSize}px`,
      };
    },

    iconClassName() {
      switch (this.service) {
        case 'github':
          return 'fab fa-github';
        case 'facebook':
          return 'fab fa-facebook';
        case 'google':
          return 'fab fa-google';
        case 'line':
          return 'fab fa-line';
        default:
          return 'fas fa-people-arrows';
      }
    },
  },
};
</script>

<style scoped>
.social-connecting-header {
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;

  margin-bottom: 40px;
}

.social-connecting-header__icon {
  display: inline-block;

  box-sizing: border-box;
  min-width: var(--social-connecting-header-size, 72px);
  height: var(--social-connecting-header-size, 72px);
  line-height: 1;

  color: var(--social-connecting-header-color, #1c2833);
  font-size: var(--social-connecting-header-size, 72px);
  text-align: center;
}

.social-connecting-header__icon i {
  position: relative;
}

.social-connecting-header__connecting-icon {
  position: absolute;
  right: -8px;
  bottom: -8px;

  box-sizing: border-box;
  width: 32px;
  height: 32px;
  padding-top: 5px;
  background-color: #808b96;
  border: 2px solid #d5d8dc;
  border-radius: 50%;

  color: #fff;
  text-align: center;
  font-size: 16px;
}
</style>
