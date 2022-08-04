<template>
  <div class="login-page">
    <div class="sign-in-box-pos">
      <ClientPane v-if="clientMode" />
      <AdminPane v-else />
    </div>
    <button @click="clientMode = !clientMode">Switch Mode</button>
  </div>
</template>

<script>
import ClientPane from '../components/login/ClientPane';
import AdminPane from '../components/login/AdminPane';

export default {
  components: { ClientPane, AdminPane },

  data() {
    return {
      clientMode: true,
    };
  },
};
</script>

<style scoped>
.login-page {
  height: 100%;
}

.sign-in-box-pos {
  padding: 15vh 0 0;
}
</style>
