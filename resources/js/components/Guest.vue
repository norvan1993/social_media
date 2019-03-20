<template>
  <div>
    <guest-header></guest-header>
    <router-view :errors="errors">
      <slot></slot>
    </router-view>
  </div>
</template>

<script>
import GuestHeader from "./GuestHeader.vue";
export default {
  props: ["errors"],
  components: {
    "guest-header": GuestHeader
  }
};
</script>

<style scoped>
</style>
