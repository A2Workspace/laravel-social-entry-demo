<template>
  <CardDescsItem :label="label" v-if="isEmpty">
    <template #default>
      <span class="__emtpy">{{ emptyText }}</span>
    </template>

    <template #actions>
      <CardButton @click="handleConnection">{{ connectionButtonText }}</CardButton>
    </template>
  </CardDescsItem>

  <CardDescsItem :label="label" v-else>
    <template #default>
      <span>{{ value }}</span>
    </template>

    <template #actions>
      <CardButton @click="handleDisonnection">{{ disconnectionButtonText }}</CardButton>
    </template>
  </CardDescsItem>
</template>

<script>
import CardButton from './CardButton';
import CardDescsItem from './CardDescsItem';

export default {
  components: { CardButton, CardDescsItem },

  props: {
    value: {},
    provider: {
      require: true,
    },
    emptyText: {
      default: '-----',
    },
    connectionButtonText: {
      default: 'Connect',
    },
    disconnectionButtonText: {
      default: 'Disonnect',
    },
  },

  inject: ['handleSocialConnection', 'handleSocialDisonnection'],

  methods: {
    handleConnection() {
      this.handleSocialConnection(this.provider);
    },

    handleDisonnection() {
      this.handleSocialDisonnection(this.provider, this.value);
    },
  },

  computed: {
    label() {
      const provider = String(this.provider).toLowerCase();

      return provider.charAt(0).toUpperCase() + provider.substr(1) + ' Account';
    },

    isEmpty() {
      return !this.value;
    },
  },
};
</script>

<style scoped>
.__emtpy {
  color: #d1d1d1;
  font-size: 0.8em;
  letter-spacing: 0.1em;
  user-select: none;
}
</style>
