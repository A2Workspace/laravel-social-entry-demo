<template>
  <div class="sign-in-box">
    <div class="sign-in-box__inner">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SignInBox',
};
</script>

<style>
.sign-in-box {
  overflow: hidden;
  position: relative;

  width: 400px;
  height: auto;
  margin: 0 auto;
  background: var(--sign-in-box-bg, #fff);
  border: 1px solid #efefef;
  border-radius: 2px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);

  transition: height 1s;
}

/* =============================================================================
* = .sign-in-box__top-actions
** ========================================================================== */

.sign-in-box__top-actions {
  display: flex;
  flex-flow: row nowrap;
  justify-content: space-between;
  align-items: center;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  padding: 10px 10px 0;
  color: #555;
  font-size: 14px;
  line-height: 1;
  user-select: none;
}

.sign-in-box__top-actions a {
  color: #555;
  text-decoration: none;
}

.sign-in-box__top-actions a:hover {
  color: #8f8f8f;
}

/* =============================================================================
* = .sign-in-box__inner
** ========================================================================== */

.sign-in-box__inner {
  position: relative;
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  padding: 40px 30px 40px;
}

/* =============================================================================
* = .sign-in-box__form
** ========================================================================== */

.sign-in-box__form.--shaking {
  animation: shaking 0.5s;
}

@keyframes shaking {
  10% {
    transform: translate(-8%, 0);
  }

  25% {
    transform: translate(8%, 0);
  }

  40%,
  70% {
    transform: translate(-2%, 0);
  }

  55%,
  85% {
    transform: translate(2%, 0);
  }
}

.sign-in-box__error-message {
  margin: 0;
  margin-bottom: 1em;
  color: var(--color-error, #ec4646);
  font-size: 15px;
  text-align: center;
  line-height: 1;
}

/* =============================================================================
* = .sign-in-box__social-entry
** ========================================================================== */

.sign-in-box__social-entry {
  margin-bottom: 20px;
}

.sign-in-box__social-entry-text {
  margin: 0;
  margin-bottom: 18px;
  color: #777;
  font-size: 14px;
  text-align: center;
}

.sign-in-box__social-entry-links {
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  align-items: center;
  gap: 10px;
}
</style>
