<template>
  <div class="row justify-content-center">
    <div class="col-sm-6">
      <create-post :user="user" :csrf="csrf"></create-post>
    </div>
  </div>
</template>

<script>
import CreatePost from "./CreatePost.vue";
export default {
  props: ["csrf"],
  data() {
    return {};
  },
  props: ["user"],
  components: {
    "create-post": CreatePost
  }
};
</script>

<style scoped>
</style>
