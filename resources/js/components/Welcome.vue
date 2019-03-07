<template>
  <div>
    <!--new row-->
    <header-b></header-b>
    <!--new row-->
    <div class="row mt-1 back-clr justify-content-center align-items-center">
      <p class="my-auto d-inline-block align-middle p-2">why should you choose us</p>
    </div>
    <!---->
    <div class="row m-3 justify-content-center">
      <div class="card col-md-2 m-3" style="width: 18rem;">
        <img class="card-img-top" src="/svg/icons/findFriends.svg" alt="Card image cap">
        <div class="card-body">
          <p
            class="card-text"
          >Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        </div>
      </div>
      <div class="card col-md-2 m-3" style="width: 18rem;">
        <img class="card-img-top" src="/svg/icons/basket.svg" alt="Card image cap">
        <div class="card-body">
          <p
            class="card-text"
          >Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        </div>
      </div>
      <div class="card col-md-2 m-3" style="width: 18rem;">
        <img class="card-img-top" src="/svg/icons/readArticles.svg" alt="Card image cap">
        <div class="card-body">
          <p
            class="card-text"
          >Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import HeaderBeforeLogIn from "./HeaderBeforeLogIn.vue";
export default {
  components: {
    "header-b": HeaderBeforeLogIn
  }
};
</script>

<style scoped>
.back-clr {
  background-color: rgb(29, 29, 127);
  color: white;
}
</style>
