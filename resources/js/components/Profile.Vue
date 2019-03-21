<template>
  <div>
    you are in profile of {{id}}
    <router-view></router-view>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      id: this.$route.params.id
    };
  },
  watch: {
    $route(to, from) {
      this.id = to.params.id;
    }
  },
  methods: {
    loadusername() {}
  },
  created: function() {
    axios
      .get("http://carmeer.com/api/users")
      .then(res => alert(res.data.data[0].name));
  }
};
</script>

<style scoped>
</style>
