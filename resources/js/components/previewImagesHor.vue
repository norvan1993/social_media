<template>
  <div class="imagesContainer">
    <div class="item" v-if="first">
      <div class="itemInner" v-for="first"></div>
    </div>
    <div class="item" v-if="second">
      <div class="itemInner" v-for="second"></div>
    </div>
  </div>
</template>
<script>
export default {};
</script>

<style scoped>
.imagesContainer {
  display: flex;
  flex-direction: column;
  width: 400px;
  height: 250px;
  background-color: red;
  border: solid 5px red;
}
.item {
  display: flex;
  flex-direction: row;
  flex-grow: 1;
  width: 100%;
  border: solid 2px yellow;
  background-color: blue;
}
.itemInner {
  flex-grow: 1;
  height: 100%;
  border: solid 2px green;
  background-color: black;
}
</style>