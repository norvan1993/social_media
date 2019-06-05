<template>
  <div v-if="user">
    <div class="row p-3 border">
      <div class="col-sm-6" align="center">
        <div class="profilePhoto float-left" @click="openScreenOverlay()">
          <div style="width:100%; height:100%; position:relative;">
            <img :src="profilePhoto" class="profileImg">
            <div class="profilePhotoOverlay"></div>
          </div>
        </div>
        <p class="userName h3 float-left ml-3">
          {{user.name}}
          <br>
          <button
            class="btn btn-primary"
            v-if="profileRelationType=='not_friend'"
            @click="addFriend()"
          >add friend</button>
          <button
            class="btn btn-primary"
            v-if="profileRelationType=='sender'"
            @click="respondTofriendRequest()"
          >respond</button>
          <button
            class="btn btn-primary"
            v-if="profileRelationType=='receiver'"
            @click="cancelFriendRequest()"
          >cancel</button>
        </p>
      </div>
    </div>

    <div class="row p-3 border justify-content-center">
      <router-link class="p-3" :to="'/profile/'+this.$route.params.id" exact>timeline</router-link>
      <hr>
      <router-link class="p-3" :to="'/profile/'+this.$route.params.id+'/friends'" exact>friends</router-link>
      <hr>
      <router-link class="p-3" :to="'/profile/'+this.$route.params.id+'/photos'" exact>photos</router-link>
      <hr>
      <router-link class="p-3" :to="'/profile/'+this.$route.params.id+'/about'" exact>about</router-link>
    </div>
  </div>
</template>

<script>
export default {
  props: ["id", "user", "profileRelationType", "screenOverlay", "profilePhoto"],

  methods: {
    openScreenOverlay() {
      this.$emit("openScreenOverlay");
    },
    addFriend() {}
  }
};
</script>

<style  scoped>
.profilePhoto {
  width: 100px;
  height: 100px;
}
.profileImg {
  position: absolute;
  top: 0px;
  left: 0px;
  width: 100px;
  height: 100px;
  border: 5px solid white;
  box-shadow: 0px 0px 10px 0.5px rgb(159, 159, 159);
}
.profilePhotoOverlay {
  position: absolute;
  top: 0px;
  left: 0px;
  width: 100px;
  height: 100px;
}
.profilePhotoOverlay:hover {
  background-color: rgba(255, 255, 255, 0.4);
  cursor: pointer;
}
.userName {
  position: relative;
  top: 25px;
  text-transform: capitalize;
}
.router-link-active {
  background-color: rgb(234, 234, 234);
}
</style>
