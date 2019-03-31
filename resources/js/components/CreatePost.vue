<template>
  <div class="card mt-3">
    <div class="card-header">
      <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg">
      <span class="ml-2" style="cursor:pointer;">{{user.name}}</span>
    </div>
    <div class="card-body">
      <h4>
        <input
          type="text"
          placeholder="Title Here"
          class="d-block border-0"
          style="width:100%;"
          v-model="title"
        >
        <hr>
      </h4>
      <div class="card-text">
        <textarea
          placeholder="write something"
          rows="4"
          class="d-block ml-3 border-0"
          style="width:100%; resize:none;"
          v-model="body"
        ></textarea>
      </div>
      <div class="d-block filesContainer">
        <div
          v-for="(file, key)  in files"
          class="mt-3 mb-3 ml-3 rounded shadow imageBlock"
          :style="{backgroundImage:'url('+convertToData(file)+')'}"
        >
          <div class="imageOverlay"></div>
          <img src="/ic/cancel.png" class="m-auto optionsArrow" @click="removeImage(key)">
        </div>
      </div>
      <hr>
      <button
        type="button"
        class="btn btn-outline-success d-block mr-3 float-right"
        @click="post()"
      >Post</button>
      <input @change="appendPhotos()" type="file" ref="filesSelector" hidden multiple>
      <button
        type="button"
        class="btn btn-outline-success d-block mr-3 float-right"
        @click="chooseFiles()"
      >Choose Files</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["csrf"],
  data() {
    return {
      files: [],
      body: "",
      title: ""
    };
  },
  props: ["user"],
  methods: {
    //append the selected files to the files array in the structure of data
    appendPhotos() {
      for (let i = 0; i < this.$refs.filesSelector.files.length; i++) {
        this.files.push(this.$refs.filesSelector.files[i]);
      }
    },
    //click on hidden input(type file) when the user click on choose files button
    chooseFiles() {
      this.$refs.filesSelector.click();
    },
    //convet the given file object to data
    convertToData(file) {
      return URL.createObjectURL(file);
    },
    removeImage(key) {
      this.files.splice(key, 1);
    },
    post() {
      let privacy = {
        status: "public"
      };
      privacy = JSON.stringify(privacy);
      var form = new FormData();

      form.append("title", this.title);
      form.append("body", this.body);
      form.append("privacy", privacy);
      for (var i in this.files) {
        form.append("photos[]", this.files[i]);
      }

      axios({
        method: "post", //you can set what request you want to be
        url: "http://carmeer.com/api/posts",
        data: form,
        headers: {
          Authorization: "Bearer " + localStorage.getItem("access_token"),
          "Content-Type": "multipart/form-data"
        }
      })
        .then(res => this.handlePost(res.data[0]))
        .catch(error => alert(JSON.stringify(error.response)));
    },
    handlePost(data) {
      alert(data.message);
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
.filesContainer {
  width: 100%;
  height: 150px;
  background-color: white;
  padding-left: 10px;

  overflow-x: auto;
  overflow-y: hidden;
  white-space: nowrap;
}
.filesContainer:after {
  content: "";
  display: inline-block;
  height: 100%;
  width: 10px;
}
.imageBlock {
  display: inline-block;
  width: 100px;
  height: 100px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-color: rgb(202, 202, 202);
  cursor: pointer;
}
.imageOverlay {
  position: relative;
  width: 100px;
  height: 100px;
  background-color: rgba(165, 165, 165, 0.6);
  visibility: hidden;
}
.imageBlock:hover .imageOverlay {
  visibility: visible;
}
.optionsArrow {
  position: relative;
  display: block;
  top: -65px;
  width: 30px;
  visibility: hidden;
  transition: all 0.2s ease-in-out;
}
.imageBlock:hover .optionsArrow {
  visibility: visible;
}
.optionsArrow:hover {
  transform: scale(1.3);
}
</style>
