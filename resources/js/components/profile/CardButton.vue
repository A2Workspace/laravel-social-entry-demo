<template>
  <button class="card-button">
    <slot>{{ text }}</slot>
  </button>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    text: {
      default: 'Button',
    },
  },
};
</script>

<style scoped>
.card-button {
  box-sizing: border-box;
  width: 100%;
  height: 32px;
  padding: 0 16px;
  background-color: #3f475a;
  border: 0;
  border-radius: 2px;
  outline: 0;

  color: #fafafa;
  font-size: 13px;
  text-align: center;
  letter-spacing: 0.2px;

  cursor: pointer;
  user-select: none;
}

.card-button:hover {
  background-color: #545f78;
  transition: background-color 200ms ease-out;
}

.card-button:active {
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.2) inset;
  padding-top: 3px;
}

.card-button:disabled {
  background-color: #3f475a !important;
  color: #ccc;
  cursor: progress;
}
</style>
