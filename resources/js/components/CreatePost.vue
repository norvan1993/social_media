<template>
  <div class="card mt-3">
    <div v-for="file in files">
      <file-reader :file="file" @test="test"></file-reader>
    </div>
    <div class="card-header">
      <!--
      <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg">
      -->

      <span class="ml-2" style="cursor:pointer;">{{user.name}}</span>
    </div>

    <div class="card-body">
      <h4>
        <input
          type="text"
          placeholder="Title Here"
          class="d-block border-0"
          style="width:100%;"
          @change="appendTitle()"
        >
        <hr>
      </h4>
      <div class="card-text">
        <textarea
          @change="appendBody()"
          placeholder="write something"
          rows="4"
          class="d-block ml-3 border-0"
          style="width:100%; resize:none;"
        ></textarea>
      </div>
      <div class="d-block filesContainer"></div>
      <hr>
      <button type="button" class="btn btn-outline-success d-block mr-3 float-right">Post</button>
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
import FileReader from "./FileReader.vue";
export default {
  data() {
    return {
      files: [],
      test: ""
    };
  },
  components: {
    "file-reader": FileReader
  },
  watch: {
    files: function() {}
  },
  props: ["user"],
  methods: {
    appendPhotos() {
      for (let i = 0; i < this.$refs.filesSelector.files.length; i++) {
        this.files.push(this.$refs.filesSelector.files[i]);
      }
    },

    chooseFiles() {
      this.$refs.filesSelector.click();
    },
    receive(data) {
      this.test = data;
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
</style>
