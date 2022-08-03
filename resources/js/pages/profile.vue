<template>
  <div class="profile-page">
    <Card header="Profile">
      <CardDescs>
        <CardDescsItem label="Username">{{ user.username }}</CardDescsItem>
        <CardDescsItem label="Nickname">{{ user.display_name }}</CardDescsItem>
        <CardDescsSocialite provider="github" />
        <CardDescsSocialite provider="facebook" />
        <CardDescsSocialite provider="google" />
        <CardDescsSocialite provider="line" />
      </CardDescs>
    </Card>
  </div>
</template>

<script>
import Card from '../components/profile/Card';
import CardDescs from '../components/profile/CardDescs';
import CardDescsItem from '../components/profile/CardDescsItem';
import CardDescsSocialite from '../components/profile/CardDescsSocialite';
import { resetParams } from '../mixins/SocialEntry';

export default {
  components: {
    Card,
    CardDescs,
    CardDescsItem,
    CardDescsSocialite,
  },

  inject: ['$auth', '$socialEntry'],

  provide() {
    return {
      handleSocialConnection: this.handleSocialConnection,
      handleSocialDisonnection: this.handleSocialDisonnection,
    };
  },

  methods: {
    handleSocialConnection(provider) {
      this.$socialEntry.authorize(provider).redirect();
    },

    async completeSocialConnection() {
      const response = await this.$socialEntry.completeAuthorization();

      if (!response.data.new_user || response.data.local_user_id !== null) {
        window.alert('This social account is already connected to a different user');

        return false;
      }

      await this.$socialEntry.connectWithToken(response.data.access_token);

      window.alert(`The ${response.data.provider} account is connected`);
      window.location.replace('/');
    },

    async handleSocialDisonnection(provider, identifier) {
      await this.$socialEntry.disconnect(provider, identifier);

      window.location.replace('/');
    },
  },

  computed: {
    user() {
      return this.$auth.getUser();
    },
  },

  async mounted() {
    await this.completeSocialConnection();
  },
};
</script>

<style scoped>
.profile-page {
  box-sizing: border-box;
  padding-top: 110px;
  height: 100%;
}
</style>
