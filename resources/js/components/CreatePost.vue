<template>
  <div class="card mt-3">
    <img class="profileImg" ref="please">
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
export default {
  data() {
    return {
      files: [],
      test: ""
    };
  },
  watch: {},
  computed: {},
  props: ["user"],
  methods: {
    //append the files selected to the files array and convert the first file in the array to data
    appendPhotos() {
      for (let i = 0; i < this.$refs.filesSelector.files.length; i++) {
        this.files.push(this.$refs.filesSelector.files[i]);
      }

      alert(this.convertToData());
    },
    //click on hidden input(type file) when the user click on choose files button
    chooseFiles() {
      this.$refs.filesSelector.click();
    },
    //convet the first file in the files array to data
    convertToData() {
      if (this.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
          this.$refs.please.src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
      }
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
