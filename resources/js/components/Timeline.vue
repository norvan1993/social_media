<template>
  <div class="row justify-content-center">
    <div class="col-sm-6">
      <create-post :user="user"></create-post>
    </div>
  </div>
</template>

<script>
import CreatePost from "./CreatePost.vue";
export default {
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
