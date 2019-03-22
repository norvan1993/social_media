<template>
  <div>
    you are in profile of {{id}}
    <div v-if="user">
      <img :src="'http://carmeer.com/photo/'+user.file">
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      id: this.$route.params.id,
      user: {}
    };
  },
  watch: {
    $route(to, from) {
      this.id = to.params.id;
    }
  },
  methods: {
    loadUserData(obj) {
      this.user = obj;
    }
  },
  created: function() {
    axios
      .get("http://carmeer.com/api/users/" + this.$route.params.id)
      .then(res => this.loadUserData(res.data[0]));
  }
};
</script>

<style scoped>
</style>
