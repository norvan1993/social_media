<template>
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
        <a class="btn signup" href="/register">SignUp</a>
        <a class="btn signin" href="/login">SignIn</a>
      </div>
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
</style>
