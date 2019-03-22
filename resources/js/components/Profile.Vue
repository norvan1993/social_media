<template>
  <div>
    you are in profile of {{id}}
    <div v-if="user">
      <div class="p-3 border">
        <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg">
      </div>
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
.profileImg {
  width: 100px;
  height: 100px;
  border: 5px solid white;
  box-shadow: 0px 0px 10px 0.5px rgb(159, 159, 159);
}
</style>
