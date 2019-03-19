<template>
  <div class="row align-items-center">
    <brand></brand>
    <div class="offset-md-4 col-md-4" align="center">
      <guest-buttons></guest-buttons>
    </div>
  </div>
</template>

<script>
import Brand from "./Brand.vue";
import GuestButtons from "./GuestButtons.vue";
export default {
  components: {
    brand: Brand,
    "guest-buttons": GuestButtons
  }
};
</script>

<style scoped>
</style>
