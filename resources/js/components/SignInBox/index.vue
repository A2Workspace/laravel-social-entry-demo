<template>
  <div class="sign-in-box">
    <SignInSection />
  </div>
</template>

<script>
import SignInSection from './SignInSection';

export default {
  name: 'SignInBox',
  components: { SignInSection },

  inject: ['$auth', '$socialEntry'],

  data() {
    return {
      status: 'general',
    };
  },

  methods: {},

  computed: {},

  mounted() {
    this.$socialEntry.completeAuthorization().then((response) => {
      if (response.data.new_user || response.data.local_user_id == null) {
        window.alert('該帳號尚未註冊');

        return;
      }

      this.$socialEntry.loginWithToken(response.data.access_token).then((authResponse) => {
        this.$auth.setUserToken(authResponse.data.access_token);
      });
    });
  },
};
</script>

<style src="./style.css">
</style>
