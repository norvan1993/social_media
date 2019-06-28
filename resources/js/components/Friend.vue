<template>
  <span>
    <v-card class="grey lighten-4">
      <v-list-tile class="grow">
        <v-list-tile-avatar color="grey darken-3">
          <v-img class="elevation-6" :src="profilePhoto"></v-img>
        </v-list-tile-avatar>

        <v-list-tile-content>
          <v-list-tile-title>{{user.name}}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-card>
  </span>
</template>

<script>
export default {
  props: ["friendId"],
  data() {
    return {
      profilePhoto: "",
      user: {}
    };
  },
  methods: {
    loadUserData(obj) {
      this.user = obj;
    },
    loadProfilePhoto(event) {
      console.log(event);
      if (event.photoName) {
        this.profilePhoto = "http://carmeer.com/photo/" + event.photoName;
      } else {
        this.profilePhoto = "http://carmeer.com/ic/profile.png";
      }
    }
  },
  created: function() {
    //get user infos
    axios
      .get("http://carmeer.com/api/users/" + this.friendId)
      .then(res => this.loadUserData(res.data[0]));
    //get profile photo
    axios
      .get("http://carmeer.com/api/users/" + this.friendId + "/profile_photo")
      .then(res => this.loadProfilePhoto(res.data));
  }
};
</script>

<style lang="scss" scoped>
</style>