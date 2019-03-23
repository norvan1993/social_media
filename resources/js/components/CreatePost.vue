<template>
  <div class="card mt-3">
    <div class="card-header">
      <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg">
      <span class="ml-2" style="cursor:pointer;">{{user.name}}</span>
    </div>
    <div id="outerProgressBar" class="progress" style="height:2px; display:none;">
      <div
        id="innerProgressBar"
        class="progress-bar bg-success"
        role="progressbar"
        aria-valuenow="50"
        aria-valuemin="0"
        aria-valuemax="100"
      ></div>
    </div>

    <div class="card-body">
      <h4>
        <input
          id="formTitle"
          type="text"
          placeholder="Title Here"
          class="d-block border-0"
          style="width:100%;"
        >
        <hr>
      </h4>
      <div class="card-text">
        <textarea
          id="formBody"
          placeholder="write something"
          rows="4"
          class="d-block ml-3 border-0"
          style="width:100%; resize:none;"
        ></textarea>
      </div>
      <div class="d-block filesContainer"></div>
      <hr>
      <button
        type="button"
        class="btn btn-outline-success d-block mr-3 float-right"
        onclick="post()"
      >Post</button>
      <button
        type="button"
        class="btn btn-outline-success d-block mr-3 float-right"
        onclick="document.getElementById('file').click();"
      >Choose Files</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"]
};
</script>

<style scoped>
.profileImg {
  width: 40px;
  height: 40px;
  border: 1px solid black;
}
</style>