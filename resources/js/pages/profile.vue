<template>
  <div class="profile-page">
    <Card header="Profile">
      <CardDescs>
        <CardDescsItem label="Username">{{ user.username }}</CardDescsItem>
        <CardDescsItem label="Nickname">{{ user.display_name }}</CardDescsItem>
        <CardDescsSocialItem provider="github" />
        <CardDescsSocialItem provider="facebook" />
        <CardDescsSocialItem provider="google" />
        <CardDescsSocialItem provider="line" />
      </CardDescs>
    </Card>
  </div>
</template>

<script>
import Card from '../components/profile/Card';
import CardDescs from '../components/profile/CardDescs';
import CardDescsItem from '../components/profile/CardDescsItem';
import CardDescsSocialItem from '../components/profile/CardDescsSocialItem';
import ProfileFormItem from '../components/profile/ProfileFormItem';

export default {
  components: {
    Card,
    CardDescs,
    CardDescsItem,
    ProfileFormItem,
    CardDescsSocialItem,
  },

  inject: ['$auth'],

  computed: {
    user() {
      return this.$auth.user();
    },
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
