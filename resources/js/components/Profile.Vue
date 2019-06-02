<template>
    <div>
        <div v-if="screenOverlay" class="screenOverlay"></div>
        <div v-if="screenOverlay" class="updateProfilePhotoBoxContainer">
            <div class="row justify-content-center" style="margin-top:100px;">
                <div class="bg-white col-sm-6" style="height:400px;" align="center">
                    <div class="row justify-content-center">
                        <img :src="profilePhoto" class="profileImgUpdate mt-2">
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xs-8 col-sm-6 col-md-5 mt-2">
                            <input
                                @change="previewProfilePhoto()"
                                type="file"
                                ref="profilePhotoSelector"
                                hidden
                            >
                            <button
                                class="btn btn-primary btn-block"
                                @click="$refs.profilePhotoSelector.click()"
                            >choose photo</button>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xs-8 col-sm-6 col-md-5 mt-2">
                            <button class="btn btn-primary btn-block">update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--header-->
        <vue-header></vue-header>
        <div v-if="user">
            <div class="profileTemp p-3 border" align="center">
                <div @click="openScreenOverlay()">
                    <img :src="profilePhoto" class="profileImg">
                    <div class="profilePhotoOverlay"></div>
                </div>

                <p class="mt-3 h3 userName">{{user.name}}</p>
                <router-link
                    class="d-block p-3"
                    :to="'/profile/'+this.$route.params.id"
                    exact
                >timeline</router-link>
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
import axios from "axios";
export default {
    data() {
        return {
            id: this.$route.params.id,
            user: {},
            profilePhoto: "",
            screenOverlay: false
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
        loadprofilePhoto(data) {
            if (data.photoName) {
                this.profilePhoto =
                    "http://carmeer.com/photo/" + data.photoName;
            } else {
                this.profilePhoto = "http://carmeer.com/ic/profile.png";
            }
        },
        openScreenOverlay() {
            this.screenOverlay = true;
        },
        previewProfilePhoto() {}
    },
    components: {
        "vue-header": Header
    },
    created: function() {
        //get user infos
        axios
            .get("http://carmeer.com/api/users/" + this.$route.params.id)
            .then(res => this.loadUserData(res.data[0]));

        axios
            .get(
                "http://carmeer.com/api/users/" +
                    this.$route.params.id +
                    "/profile_photo"
            )
            .then(res => this.loadprofilePhoto(res.data));
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
.updateProfilePhotoBoxContainer {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 4;
}
.profileImgUpdate {
    width: 250px;
    height: 250px;
    border: 2px solid white;
    box-shadow: 0px 0px 10px 0.5px rgb(159, 159, 159);
}
</style>
