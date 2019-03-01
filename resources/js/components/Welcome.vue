<template>
  <div>
    <!--new row-->
    <div class="row align-items-center">
      <div class="col-md-4" align="center">
        <p class="my-auto ml-2 mr-2 brand d-inline-block align-middle float-md-left">Carmeer</p>
      </div>
      <div class="offset-md-4 col-md-4" align="center">
        <div
          class="btn-group border float-md-right mr-1 ml-1"
          role="group"
          aria-label="Basic example"
        >
          <button type="button" class="btn signin">SignIn</button>
          <button type="button" class="btn signup">SignUp</button>
        </div>
      </div>
    </div>
    <!--new row-->
    <div class="row mt-1 back-clr justify-content-center align-items-center">
      <p class="my-auto d-inline-block align-middle p-2">why should you choose us</p>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.brand {
  font-size: 32px;
  font-family: "Monoton";
  color: rgb(29, 29, 127);
}
.signin {
  color: white;
  background-color: rgb(29, 29, 127);
}
.signup {
  color: rgb(29, 29, 127);
  background-color: white;
}
.back-clr {
  background-color: rgb(29, 29, 127);
  color: white;
}
</style>
