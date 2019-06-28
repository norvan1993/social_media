<template>
  <v-layout row wrap class="pa-3">
    <v-flex v-for="friendId in friendsList" key="friendId" xs12 sm4 md2 class="black--text ma-2">
      <friend :friendId="friendId"></friend>
    </v-flex>
  </v-layout>
</template>

<script>
import Friend from "./Friend.vue";
export default {
  props: ["user", "profilePhoto", "profileRelationType"],
  data() {
    return {
      friendsList: []
    };
  },
  components: {
    friend: Friend
  },
  methods: {
    handleFriendsList(friendsList) {
      this.friendsList = friendsList;
    }
  },
  created: function() {
    axios
      .get(
        "http://carmeer.com/api/friendship/" + this.user.id + "/friends_list",
        {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("access_token")
          }
        }
      )
      .then(res => this.handleFriendsList(res.data.friendsList));
  }
};
</script>

<style lang="scss" scoped>
</style>
