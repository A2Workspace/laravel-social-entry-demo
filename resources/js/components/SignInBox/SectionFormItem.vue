<template>
  <div class="section-form-item">
    <input class="section-form-item__input" v-bind="$attrs" v-model="inputValue" placeholder=" " />
    <label class="section-form-item__label">{{ label }}</label>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    label: {
      default: 'untitled',
    },
  },

  computed: {
    inputValue: {
      get() {
        return this.value;
      },

      set(newValue) {
        if (newValue != this.value) {
          this.$emit('input', newValue);
        }
      },
    },
  },
};
</script>

<style scoped>
.section-form-item {
  display: block;
  position: relative;
  margin: 20px 0;
  font-size: 14px;
}

.section-form-item__label {
  position: absolute;
  top: 10px;
  left: 8px;
  box-sizing: border-box;
  padding: 6px 8px;
  color: #888;
  font-size: 1em;
  line-height: 1;

  cursor: text;
  user-select: none;
  transition: 150ms ease-in;
}

.section-form-item__input:focus ~ .section-form-item__label,
.section-form-item__input:not(:placeholder-shown).section-form-item__input:not(:focus) ~ .section-form-item__label {
  top: -11px;
  left: 8px;
  font-size: 0.85em;
  background-color: var(--sign-in-box-bg-color, #fff);
}

.section-form-item__input {
  box-sizing: border-box;

  width: 100%;
  height: 100%;
  padding: 15px 14px 12px;
  background-color: #fff;
  border: 1px solid #dcdfe6;
  border-radius: 2px;

  outline: 0;
}

.section-form-item__input:focus {
  border: 1px solid #7984a0;
}

.section-form-item__input:disabled {
  color: #aaa;
  cursor: progress;
}
</style>
