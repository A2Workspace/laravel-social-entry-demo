<template>
  <button class="section-button" :disabled="processing">
    <span v-show="!processing">
      <slot>{{ text }}</slot>
    </span>
    <span class="section-button__icon" v-show="processing">
      <i :class="icon" />
    </span>
  </button>
</template>

<script>
export default {
  props: {
    icon: {
      default: 'far fa-spinner-third',
    },
    text: {
      default: 'Submit',
    },
    processing: {
      default: false,
    },
  },
};
</script>

<style scoped>
.section-button {
  box-sizing: border-box;
  width: 100%;
  height: 44px;
  margin-top: 2px;
  background-color: #3f475a;
  border: 0;
  border-radius: 2px;

  color: #fafafa;
  font-size: 15px;
  text-align: center;
  letter-spacing: 0.2px;

  outline: 0;
  cursor: pointer;
  user-select: none;
}

.section-button:hover {
  background-color: #545f78;
  transition: background-color 200ms ease-out;
}

.section-button:active {
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.2) inset;
  padding-top: 3px;
}

.section-button:disabled {
  background-color: #3f475a !important;
  color: #ccc;
  cursor: progress;
}

.section-button__icon {
  font-size: 1.3em;
}

.section-button__icon i {
  animation: spin 900ms linear infinite;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}
</style>
