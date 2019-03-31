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
      </div>
      <div class="d-block filesContainer">
        <div
          v-for="file  in files"
          class="mt-3 mb-3 ml-3 rounded shadow imageBlock"
          :style="{backgroundImage:'url('+convertToData(file)+')'}"
        ></div>
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
export default {
  props: ["user", "post"]
};
</script>

<style scoped>
</style>
