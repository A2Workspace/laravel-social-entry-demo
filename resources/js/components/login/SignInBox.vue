<template>
  <div class="sign-in-box">
    <div class="sign-in-box__inner">
      <h2 class="sign-in-box__header">Social Entry</h2>
      <form class="sign-in-box__form">
        <input type="text" placeholder="Username" v-model="form.username" />
        <input type="password" placeholder="Password" autocomplete="new-password" v-model="form.password" />
        <button @click="submitForm">Sign In</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  inject: ['login'],

  data() {
    return {
      form: {
        username: '',
        password: '',
      },
    };
  },

  methods: {
    async submitForm() {
      const { username, password } = this.form;

      await this.login({ username, password });
    },
  },
};
</script>

<style scoped>
.sign-in-box {
  position: relative;
  box-sizing: border-box;
  width: 400px;
  min-height: 520px;
  margin: 30px auto;
  padding: 40px 30px 30px;
  border: 1px solid #efefef;
  border-radius: 2px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
  background: #fff;
}

.sign-in-box__header {
  margin: 0;
  margin-bottom: 42px;
  padding: 0;
  color: #3e3e3e;
  font-size: 1.7em;
  text-align: center;
}

.sign-in-box__form input[type='text'],
.sign-in-box__form input[type='password'] {
  box-sizing: border-box;
  width: 100%;
  height: 44px;
  margin-bottom: 18px;
  padding: 8px 14px 7px;
  background-color: #fff;
  border: 1px solid #dcdfe6;
  border-radius: 2px;
  outline: 0;
  font-size: 14px;
}

.sign-in-box__form input[type='text']:focus,
.sign-in-box__form input[type='password']:focus {
  border: 1px solid #7984a0;
}

.sign-in-box__form button {
  box-sizing: border-box;
  width: 100%;
  height: 44px;
  margin-top: 2px;
  background-color: #3f475a;
  border: 0;
  border-radius: 2px;
  outline: 0;
  color: #fff;
  font-size: 15px;
  letter-spacing: 0.2px;
  text-align: center;
  cursor: pointer;
}

.sign-in-box__form button:hover {
  background-color: #545f78;
  transition: background-color 200ms ease-out;
}
</style>
