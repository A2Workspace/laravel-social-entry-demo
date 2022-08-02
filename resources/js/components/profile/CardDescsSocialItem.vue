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
    provider: {
      require: true,
    },
    value: {},
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

  computed: {
    label() {
      const provider = String(this.provider).toLowerCase();

      return provider.charAt(0).toUpperCase() + provider.substr(1) + ' Account';
    },

    isEmpty() {
      return !this.value;
    },
  },

  methods: {
    handleConnection() {
      //
    },
    handleDisonnection() {
      //
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
