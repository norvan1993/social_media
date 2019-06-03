<template>
  <div>
    <div v-if="screenOverlay && isAuth" class="screenOverlay"></div>
    <update-profile-photo
      v-if="screenOverlay && isAuth"
      :profilePhoto="profilePhoto"
      @update="loadProfilePhoto($event)"
    ></update-profile-photo>

    <!--header-->
    <vue-header></vue-header>
    <div v-if="user">
      <div class="profileTemp p-3 border" align="center">
        <div @click="openScreenOverlay($event)">
          <img :src="profilePhoto" class="profileImg">
          <div class="profilePhotoOverlay"></div>
        </div>

        <p class="mt-3 h3 userName">{{user.name}}</p>
        <p class="mt-3 h3 userName">{{profileRelationType}}</p>
        <router-link class="d-block p-3" :to="'/profile/'+this.$route.params.id" exact>timeline</router-link>
        <hr>
        <router-link
          class="d-block p-3"
          :to="'/profile/'+this.$route.params.id+'/friends'"
          exact
        >friends</router-link>
        <hr>
        <router-link
          class="d-block p-3"
          :to="'/profile/'+this.$route.params.id+'/photos'"
          exact
        >photos</router-link>
        <hr>
        <router-link
          class="d-block p-3"
          :to="'/profile/'+this.$route.params.id+'/about'"
          exact
        >about</router-link>
      </div>
    </div>
    <router-view :user="user" :profilePhoto="profilePhoto"></router-view>
  </div>
</template>

<script>
import Header from "./Header.vue";
import UpdateProfilePhoto from "./UpdateProfilePhoto.vue";
import axios from "axios";
export default {
  data() {
    return {
      id: this.$route.params.id,
      user: {},
      profilePhoto: "",
      screenOverlay: false,
      isAuth: false,
      profileRelationType: ""
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
    },
    loadProfilePhoto(event) {
      console.log(event);
      if (event.photoName) {
        this.profilePhoto = "http://carmeer.com/photo/" + event.photoName;
      } else {
        this.profilePhoto = "http://carmeer.com/ic/profile.png";
      }
    },
    openScreenOverlay() {
      this.screenOverlay = true;
    },
    loadProfileRelationType(data) {
      console.log(data);
      this.profileRelationType = data.status;
    }
  },
  components: {
    "vue-header": Header,
    "update-profile-photo": UpdateProfilePhoto
  },
  created: function() {
    if (localStorage.getItem("auth_id") == this.$route.params.id) {
      this.isAuth = true;
    } else {
      this.isAuth = false;
    }

    //get user infos
    axios
      .get("http://carmeer.com/api/users/" + this.$route.params.id)
      .then(res => this.loadUserData(res.data[0]));
    //get profile photo
    axios
      .get(
        "http://carmeer.com/api/users/" +
          this.$route.params.id +
          "/profile_photo"
      )
      .then(res => this.loadProfilePhoto(res.data));
    //get relation with opened profile
    axios
      .get(
        "http://carmeer.com/api/friendship/" +
          this.$route.params.id +
          "/friend_status",
        {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("access_token")
          }
        }
      )
      .then(res => this.loadProfileRelationType(res.data));
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
.profileTemp {
  position: fixed;
  top: 100px;
  height: 500px;
}
.userName {
  text-transform: capitalize;
}
.router-link-active {
  background-color: rgb(234, 234, 234);
}
.profilePhotoOverlay {
  position: relative;
  width: 100px;
  height: 100px;
  background-color: rgba(255, 255, 255, 0);
  top: -100px;
}
.profilePhotoOverlay:hover {
  background-color: rgba(255, 255, 255, 0.4);
  cursor: pointer;
}
.screenOverlay {
  position: fixed;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.85);
  z-index: 3;
}
</style>
