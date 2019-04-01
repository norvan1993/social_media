<template>
  <div class="card mt-3">
    <div class="card-header">
      <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg">
      <span class="ml-2" style="cursor:pointer;">{{user.name}}</span>
    </div>
    <div class="card-body">
      <h4>
        <p>{{post.title}}</p>
        <hr>
      </h4>
      <div class="card-text">
        <p>{{post.body}}</p>
        <hr>
        <preview-images :files="post.photos"></preview-images>
      </div>
    </div>
  </div>
</template>

<script>
import PreviewImages from "./PreviewImages.vue";
export default {
  props: ["user", "post"],
  components: {
    "preview-images": PreviewImages
  }
};
</script>

<style scoped>
</style>
