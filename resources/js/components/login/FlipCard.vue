<template>
  <div class="flip-card-wrapper" ref="container">
    <div class="flip-card" :class="{ '--flip': flipped }">
      <div class="flip-card__front" ref="front">
        <slot name="front"></slot>
      </div>
      <div class="flip-card__back" ref="back">
        <slot name="back"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    value: {},
  },

  data() {
    return {
      innerStatus: false,
    };
  },

  methods: {
    flip() {
      this.flipped = !this.flipped;
    },

    correctOuterHeight() {
      const containerElement = this.$refs.container;

      if (containerElement) {
        if (!this.flipped) {
          containerElement.style.height = this.$refs.front.offsetHeight + 'px';
        } else {
          containerElement.style.height = this.$refs.back.offsetHeight + 'px';
        }
      }
    },
  },

  computed: {
    flipped: {
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

  watch: {
    flipped() {
      this.correctOuterHeight();
    },
  },

  mounted() {
    this._frontObserver = new ResizeObserver(() => {
      this.correctOuterHeight();
    });

    this._backObserver = new ResizeObserver(() => {
      this.correctOuterHeight();
    });

    this._frontObserver.observe(this.$refs.front);
    this._backObserver.observe(this.$refs.back);
  },
};
</script>

<style>
.flip-card-wrapper {
  display: block;
  transition: height 500ms ease-out;
}

.flip-card {
  position: relative;
  width: 100%;
  height: auto;
  transform-style: preserve-3d;
  transition-property: transform;
  transition-delay: 20ms;
  transition-duration: 800ms;
  transition-timing-function: cubic-bezier(0.5, 1.2, 0.5, 1.2);
}

.flip-card.--flip {
  transform: rotateY(-180deg);
}

.flip-card__front,
.flip-card__back {
  backface-visibility: hidden;
}

.flip-card__front {
  width: 100%;
  height: auto;
}

.flip-card__back {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  width: 100%;
  height: auto;
  transform: rotateY(180deg);
}
</style>
