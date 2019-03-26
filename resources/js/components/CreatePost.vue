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
      <div class="d-block filesContainer bg-primary">
        <div
          v-for="(file, key)  in files"
          class="mt-3 mb-3 ml-3 rounded shadow imageBlock"
          :style="{backgroundImage:'url('+file+')'}"
        ></div>
      </div>
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
export default {
  data() {
    return {
      files: [],
      test: ""
    };
  },
  props: ["user"],
  methods: {
    //append the files selected to the files array and convert the first file in the array to data
    appendPhotos() {
      for (let i = 0; i < this.$refs.filesSelector.files.length; i++) {
        this.files.push(this.convertToData(this.$refs.filesSelector.files[i]));
      }

      alert(this.convertToData());
    },
    //click on hidden input(type file) when the user click on choose files button
    chooseFiles() {
      this.$refs.filesSelector.click();
    },
    //convet the first file in the files array to data
    convertToData(file) {
      return URL.createObjectURL(file);
    },
    removeImage(key) {
      this.files.splice(key, 1);
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
}
</style>
