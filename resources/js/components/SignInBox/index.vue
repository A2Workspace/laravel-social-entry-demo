<template>
  <div class="sign-in-box">
    <SignOnSection v-if="'sign_on' === status" />
    <SignInSection v-else />
  </div>
</template>

<script>
import SignInSection from './SignInSection';
import SignOnSection from './SignOnSection';

export default {
  name: 'SignInBox',
  components: { SignInSection, SignOnSection },

  inject: ['$auth', '$socialEntry'],

  data() {
    return {
      status: 'sign_on',
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
