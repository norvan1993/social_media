<template>
  <div class="offset-md-4 col-md-4 col-offset-0 col-4">
    <!--log out button on md screens and larger-->
    <button @click="logOut()" class="btn logout d-none d-md-block mr-1 float-right">log out</button>
    <!--bars icon in small screens-->
    <div class="d-inline-block d-md-none float-right mr-2" style="font-size:32px;">
      <i class="fas fa-bars"></i>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    logOut() {
      axios
        .get("http://carmeer.com/api/logout", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("access_token")
          }
        })
        .then(res => this.handleToken(res.data));
    },
    handleToken(data) {
      localStorage.clear();
      this.$router.push({ path: "/" });
    }
  }
};
</script>

<style scoped>
.logout {
  color: white;
  background-color: rgb(29, 29, 127);
}
</style>
