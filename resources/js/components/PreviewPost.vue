<template>
  <div class="card mt-3" v-if="isExist">
    <div class="overlay" v-if="editPostOverlay"></div>
    <edit-post
      v-if="editPostOverlay"
      @cancelEdit="closeEditPostOverlay()"
      class="editPost"
      :user="user"
      :initialOldFiles="photos"
      :post="post"
      :profilePhoto="profilePhoto"
    ></edit-post>

    <div class="card-header" style="max-height:70px;">
      <img :src="profilePhoto" class="profileImg">
      <span class="ml-2" style="cursor:pointer;">{{user.name}}</span>
      <i class="fas fa-ellipsis-v optionsIcon float-right" @click="openList" v-if="isPostOwner"></i>
      <post-options
        @removePost="removePost()"
        :post="post"
        class="float-right"
        v-if="isOpened"
        @closeList="closeList()"
        @editPost="editPost()"
      ></post-options>
    </div>
    <div class="card-body">
      <h4>
        <p>{{post.title}}</p>
        <hr>
      </h4>
      <div class="card-text">
        <p>{{post.body}}</p>
        <hr>
      </div>
      <preview-images :user="user" v-if="photos" :files="photos" :profilePhoto="profilePhoto"></preview-images>
    </div>
  </div>
</template>

<script>
import PreviewImages from "./PreviewImages.vue";
import PostOptions from "./PostOptions.vue";
import EditPost from "./EditPost.vue";
export default {
  props: ["user", "post", "profilePhoto"],
  data() {
    return {
      photos: null,
      isPostOwner: false,
      isOpened: false,
      isExist: true,
      editPostOverlay: false
    };
  },
  methods: {
    setPhotos(photos) {
      this.photos = photos;
    },
    openList() {
      this.isOpened = true;
    },
    closeList() {
      this.isOpened = false;
    },
    removePost() {
      this.isExist = false;
    },
    editPost() {
      this.editPostOverlay = true;
    },
    closeEditPostOverlay() {
      this.editPostOverlay = false;
    }
  },
  components: {
    "preview-images": PreviewImages,
    "post-options": PostOptions,
    "edit-post": EditPost
  },
  created: function() {
    axios
      .get("http://carmeer.com/api/posts/" + this.post.id + "/photos", {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("access_token")
        }
      })
      .then(res => this.setPhotos(res.data));
    if (localStorage.getItem("auth_id") == this.user.id) {
      this.isPostOwner = true;
    } else {
      this.isPostOwner = false;
    }
  }
};
</script>

<style scoped>
.profileImg {
  width: 40px;
  height: 40px;
  border: 1px solid black;
}
.optionsIcon {
  height: 20px;
  color: black;
  cursor: pointer;
}
.overlay {
  position: fixed;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.85);
  z-index: 3;
}
.editPost {
  position: fixed;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  z-index: 4;
}
</style>

