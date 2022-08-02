<template>
  <div class="card-descs-item">
    <h5 class="card-descs-item__label">{{ label }}</h5>
    <div class="card-descs-item__inner">
      <div class="card-descs-item__main">
        <slot></slot>
      </div>
      <div class="card-descs-item__actions">
        <slot name="actions"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      default: 'untitled',
    },
  },
};
</script>

<style scoped>
.card-descs-item {
  margin: 20px 0 30px;
}

.card-descs-item__label {
  margin: 0;
  margin-bottom: 6px;
  color: #999;
  font-size: 12px;
  font-weight: normal;
  text-transform: uppercase;
  line-height: 1;
  letter-spacing: 0.4px;
  user-select: none;
}

.card-descs-item__inner {
  display: flex;
  flex-flow: row nowrap;
  justify-content: space-between;
  align-items: stretch;
}

.card-descs-item__main {
  min-height: 32px;
  line-height: 32px;
  /* padding-left: 1px; */
  color: #222;
}
</style>
