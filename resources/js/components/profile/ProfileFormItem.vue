<template>
  <div class="profile-form-item">
    <div class="profile-form-item__inner">
      <input class="profile-form-item__input" v-bind="$attrs" v-model="inputValue" />
      <label class="profile-form-item__label" :for="inputId">{{ label }}</label>
    </div>
    <p class="profile-form-item__error-message" v-show="parsedErrorMessage">{{ parsedErrorMessage }}</p>
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
.profile-form-item {
  --profile-form-item-color: #9fa3ab;
  --profile-form-item-color2: #555e77;

  display: block;
  margin: 10px 0 24px;
  padding: 8px 0 6px;
  font-size: 14px;
}

.profile-form-item__inner {
  position: relative;
}

.profile-form-item__label {
  position: absolute;
  top: -8px;
  left: 11px;
  box-sizing: border-box;
  padding: 3px 8px;
  background-color: #fff;

  border-radius: 3px;
  color: var(--profile-form-item-color);
  font-size: 0.8em;
  line-height: 1;
  text-transform: uppercase;

  cursor: text;
  user-select: none;
  transition: 150ms ease-in;
}

.profile-form-item__input:focus + .profile-form-item__label {
  color: var(--profile-form-item-color2);
}

.profile-form-item__input {
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  padding: 15px 14px 11px;
  background-color: #fff;
  border: 1px solid var(--profile-form-item-color);
  border-radius: 2px;
  outline: 0;
  color: #222;
}

.profile-form-item__input:focus {
  border: 1px solid var(--profile-form-item-color2);
}

.profile-form-item__input:disabled {
  color: #aaa;
  cursor: not-allowed;
}

.profile-form-item__error-message {
  margin: 0;
  margin-top: 0.7em;
  padding-left: 1px;
  color: var(--color-error, #ec4646);
  font-size: 13px;
  line-height: 1;
}
</style>
