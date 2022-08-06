<template>
  <footer class="footer">
    <p>Powered by</p>
    <strong>
      <a href="https://github.com/A2Workspace/laravel-social-entry">A2Workspace / Laravel-Social-Entry</a>
    </strong>
  </footer>
</template>

<script>
export default {};
</script>

<style>
.footer {
  display: block;
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  margin: 2em 0;
  color: #888;
  font-size: 14px;
  text-align: center;
  line-height: 1.4;
}

.footer p {
  margin: 0;
}

.footer strong {
  font-weight: normal;
  color: #555;
}

.footer a {
  color: inherit;
}

@media screen and (max-height: 788px) {
  .footer {
    width: 500px;
    text-align: left;
    margin: 10px;
  }
}
</style>
