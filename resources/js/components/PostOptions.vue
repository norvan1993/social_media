<template>
  <ul class="list-group mt-2">
    <li class="list-group-item" @click="editPost()">edit post</li>
    <li class="list-group-item">change privacy</li>
    <li class="list-group-item" @click="deletePost()">delete post</li>
    <li class="list-group-item" style="cursor:pointer;" @click="closeList">close</li>
  </ul>
</template>

<script>
export default {
  props: ["post"],
  methods: {
    closeList() {
      this.$emit("closeList");
    },
    deletePost() {
      var form = new FormData();
      form.append("_method", "DELETE");
      axios
        .post("http://carmeer.com/api/posts/" + this.post.id, form, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("access_token"),
            "content-type": "multipart/form-data"
          }
        })
        .then(res => this.$emit("removePost"));
    },
    editPost() {
      this.$emit("editPost");
    }
  }
};
</script>

<style scoped>
.list-group-item {
  cursor: pointer;
}
.list-group-item:hover {
  background-color: antiquewhite;
}
</style>

