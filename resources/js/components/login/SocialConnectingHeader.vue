<template>
  <div class="social-connecting-header">
    <div class="social-connecting-header__icon">
      <i :class="iconClassName">
        <div class="social-connecting-header__connecting-icon">
          <i class="fas fa-exchange-alt"></i>
        </div>
      </i>
    </div>

    <div class="connecting-icon" ref="connectingIcon" v-if="userAvatar">
      <i class="connecting-icon__dot" />
      <i class="connecting-icon__dot" />
      <i class="connecting-icon__dot" />
      <i class="connecting-icon__dot" />
      <i class="connecting-icon__dot" />
    </div>

    <div class="social-connecting-header__user-avatar" v-if="userAvatar">
      <img :src="userAvatar" />
    </div>
  </div>
</template>

<script>
let played = false;

export default {
  inheritAttrs: false,

  props: {
    service: {
      default: null,
    },
    userAvatar: {
      default: null,
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

  mounted() {
    if (!played) {
      played = true;
      this.$el.classList.add('--play');
    }
  },
};
</script>

<style scoped>
.social-connecting-header {
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  align-items: center;

  margin-bottom: 40px;
}

.social-connecting-header__icon {
  display: inline-block;

  box-sizing: border-box;
  width: var(--social-connecting-header-size, 72px);
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

.connecting-icon {
  display: flex;
  flex-flow: row nowrap;
  justify-content: space-around;
  align-items: center;
  width: 64px;

  margin: 0 18px;
}

.connecting-icon__dot {
  display: block;
  width: 5px;
  height: 5px;
  background-color: #839192;
  border-radius: 50%;

  animation-name: connecting-animation;
  animation-duration: 0.5s;
  animation-fill-mode: forwards;
  animation-play-state: paused;
}

.--play .connecting-icon__dot {
  animation-play-state: running;
  background-color: #eaeded;
}

.connecting-icon__dot:nth-child(1) {
  animation-delay: 300ms;
}

.connecting-icon__dot:nth-child(2) {
  animation-delay: 900ms;
}

.connecting-icon__dot:nth-child(3) {
  animation-delay: 1500ms;
}

.connecting-icon__dot:nth-child(4) {
  animation-delay: 2100ms;
}

.connecting-icon__dot:nth-child(5) {
  animation-delay: 2700ms;
}

.connecting-icon + .social-connecting-header__user-avatar {
  margin-left: -2px;
}

@keyframes connecting-animation {
  0% {
    background-color: #eaeded;
  }
  100% {
    background-color: #839192;
  }
}

.social-connecting-header__user-avatar {
  position: relative;

  width: var(--social-connecting-header-size, 72px);
  height: var(--social-connecting-header-size, 72px);

  animation-name: connecting-animation2;
  animation-duration: 0.5s;
  animation-fill-mode: forwards;
  animation-delay: 3000ms;
  animation-play-state: paused;
}

.social-connecting-header__user-avatar::after {
  content: '';
  display: block;
  position: absolute;
  top: -2px;
  left: -2px;
  right: -2px;
  bottom: -2px;
  z-index: 100;
  border-radius: 50%;

  border: 3px solid #85929e;
}

.--play .social-connecting-header__user-avatar {
  opacity: 0.6;
  animation-play-state: running;
}

.--play .social-connecting-header__user-avatar::after {
  border-color: #85929e;
}

.social-connecting-header__user-avatar img {
  overflow: hidden;
  object-fit: contain;
  width: 100%;
  height: 100%;
  border-radius: 50%;
}

@keyframes connecting-animation2 {
  0% {
    border-color: #d6dbdf;
    transform: scale(1.05);
  }
  100% {
    opacity: 1;
    border-color: #85929e;
  }
}
</style>
