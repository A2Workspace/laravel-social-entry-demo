<template>
  <div class="box-form-item">
    <div class="box-form-item__inner">
      <input class="box-form-item__input" v-bind="$attrs" v-model="inputValue" placeholder=" " :id="inputId" />
      <label class="box-form-item__label" :for="inputId">{{ label }}</label>
    </div>
    <p class="box-form-item__error-message" v-show="parsedErrorMessage">{{ parsedErrorMessage }}</p>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    value: {},
    label: {
      default: 'untitled',
    },
    error: {
      type: [String, Array],
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

    parsedErrorMessage() {
      let { error } = this;

      if (!error) {
        return;
      }

      if (Array.isArray(error) && typeof error[0] === 'string') {
        return error[0];
      }

      if (typeof error.toString === 'function') {
        return error.toString();
      }

      return error;
    },
  },

  created() {
    this.inputId = makeInputId();
  },
};

function makeInputId() {
  let key = Math.random().toString(36).substr(2, 5);

  return `form-item-${key}`;
}
</script>

<style scoped>
.box-form-item {
  display: block;
  margin: 20px 0;
  font-size: 14px;
}

.box-form-item__inner {
  position: relative;
}

.box-form-item__label {
  position: absolute;
  top: 10px;
  left: 8px;
  box-sizing: border-box;
  padding: 6px 7px 3px;
  color: #888;
  font-size: 1em;
  line-height: 1;

  cursor: text;
  user-select: none;
  transition: 150ms ease-in;
}

.box-form-item__input:focus + .box-form-item__label,
.box-form-item__input:not(:placeholder-shown).box-form-item__input:not(:focus) + .box-form-item__label {
  top: -11px;
  left: 7px;
  font-size: 0.85em;
  background-color: var(--sign-in-box-bg-color, #fff);
}

.box-form-item__input {
  box-sizing: border-box;

  width: 100%;
  height: 100%;
  padding: 15px 14px 12px;
  background-color: #fff;
  border: 1px solid #dcdfe6;
  border-radius: 2px;

  outline: 0;
}

.box-form-item__input:focus {
  border: 1px solid #7984a0;
}

.box-form-item__input:disabled {
  color: #aaa;
  cursor: not-allowed;
}

.box-form-item__error-message {
  margin: 0;
  margin-top: 0.7em;
  padding-left: 1px;
  color: var(--color-error, #ec4646);
  font-size: 13px;
  line-height: 1;
}
</style>
