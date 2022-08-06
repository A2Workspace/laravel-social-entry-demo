<script>
import BaseProfilePage from '../client/profile';

export default {
  extends: BaseProfilePage,

  created() {
    this.$socialEntry.setStrategy('admin');
  },
};
</script>

<style scoped>
.card::before,
.card::after {
  content: '';
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  height: 20px;
  margin: 2px;

  background-image: repeating-linear-gradient(
    -45deg,
    var(--card-bg, #fff),
    var(--card-bg, #fff) 12px,
    var(--card-decorated-color, #d2d6e0) 12px,
    var(--card-decorated-color, #d2d6e0) 24px
  );
}

.card::before {
  bottom: auto;
  height: 7px;
}

.card::after {
  top: auto;
}
</style>
