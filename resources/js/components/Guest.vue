<template>
  <div>
    <guest-header></guest-header>
    <router-view :errors="errors" :csrf="csrf"></router-view>
  </div>
</template>

<script>
import GuestHeader from "./GuestHeader.vue";
export default {
  props: ["errors", "csrf"],
  components: {
    "guest-header": GuestHeader
  }
};
</script>

<style scoped>
</style>
