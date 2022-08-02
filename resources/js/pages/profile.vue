<template>
  <div class="profile-page">
    <Card header="Profile">
      <CardDescs>
        <CardDescsItem label="Nickname">GGinin</CardDescsItem>
        <CardDescsItem label="Username">user001</CardDescsItem>
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
  padding-top: 15vh;
  height: 100%;
}
</style>
