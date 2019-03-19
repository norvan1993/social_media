<template>
  <div>
    <guest-header></guest-header>
    <router-view></router-view>
  </div>
</template>

<script>
import GuestHeader from "./GuestHeader.vue";
export default {
  components: {
    "guest-header": GuestHeader
  }
};
</script>

<style scoped>
</style>
