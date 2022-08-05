<template>
  <div class="profile-page">
    <Card header="Profile">
      <template #actions>
        <a href="#" draggable="false" @click="handleLogout">
          <span>Logout</span>
          <i class="fas fa-sign-out-alt" />
        </a>
      </template>

      <CardDescs>
        <CardDescsItem label="Username">{{ user.username }}</CardDescsItem>
        <CardDescsItem label="Nickname">{{ user.display_name }}</CardDescsItem>
        <CardDescsSocialite provider="github" :value="user.github_id" />
        <CardDescsSocialite provider="facebook" :value="user.facebook_id" />
        <CardDescsSocialite provider="google" :value="user.google_id" />
        <CardDescsSocialite provider="line" :value="user.line_id" />
      </CardDescs>
    </Card>
  </div>
</template>

<script>
import Card from '../../components/profile/Card';
import CardDescs from '../../components/profile/CardDescs';
import CardDescsItem from '../../components/profile/CardDescsItem';
import CardDescsSocialite from '../../components/profile/CardDescsSocialite';
import { resetParams } from '../../mixins/SocialEntry';

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
      const response = await this.$socialEntry.completeAuthorization().catch(() => {});

      if (!response) {
        return false;
      }

      if (!response.data.new_user || response.data.local_user_id !== null) {
        window.alert('The social account is already connected to a different user');

        resetParams();

        return false;
      }

      await this.$socialEntry.connectWithToken(response.data.access_token);

      let provider = response.data.provider;
      provider = String(provider).toLowerCase();
      provider = provider.charAt(0).toUpperCase() + provider.substr(1);

      window.alert(`The ${provider} account is connected`);
      window.location.replace('/');
    },

    async handleSocialDisonnection(provider, identifier) {
      await this.$socialEntry.disconnect(provider, identifier);

      window.location.replace('/');
    },

    async handleLogout() {
      this.$auth.logout();

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
