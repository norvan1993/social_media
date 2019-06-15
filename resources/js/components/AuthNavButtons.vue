<template>
  <div>
    <!--log out button on md screens and larger-->
    <v-btn
      @click="logOut()"
      class="indigo darken-4 white--text float-right hidden-sm-and-down"
    >log out</v-btn>
    <!--bars icon in small screens-->
    <div class="hidden-md-and-up float-right mr-2" style="font-size:32px;">
      <i class="fas fa-bars"></i>
    </div>
  </div>
</template>

<script>
import { mapMutations } from "vuex";
export default {
  methods: {
    ...mapMutations(["vuexLogOut"]),
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
      this.vuexLogOut();
      this.$router.push({ path: "/" });
    }
  }
};
</script>

<style scoped>
</style>
