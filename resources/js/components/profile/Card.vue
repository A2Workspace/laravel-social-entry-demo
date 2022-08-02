<template>
  <div class="card">
    <div class="card__inner">
      <slot name="header">
        <h2 class="card-header">{{ header }}</h2>
      </slot>
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    header: {
      default: '',
    },
  },
};
</script>

<style scoped>
.card {
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
  max-width: 600px;
  min-width: 300px;
  margin: 0 auto;
  background: #fff;
  border: 1px solid #efefef;
  border-radius: 4px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.card__inner {
  position: relative;
  box-sizing: border-box;
  padding: 25px 25px 30px;
}

.card-header {
  margin: 0;
  margin-bottom: 50px;
  color: #3e3e3e;
  line-height: 1;
  font-size: 1.5rem;
  user-select: none;
}

.card .row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  row-gap: 20px;
  margin: 20px 0;
}
</style>
