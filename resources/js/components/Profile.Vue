<template>
    <div>
        <div v-if="screenOverlay && profileRelationType=='owner'" class="screenOverlay"></div>
        <update-profile-photo
            v-if="screenOverlay &&  profileRelationType=='owner'"
            :profilePhoto="profilePhoto"
            @update="loadProfilePhoto($event)"
        ></update-profile-photo>

        <!--header-->
        <vue-header></vue-header>
        <profile-nav
            :profilePhoto="profilePhoto"
            :id="id"
            :user="user"
            :profileRelationType="profileRelationType"
            :screenOverlay="screenOverlay"
            @openScreenOverlay="openScreenOverlay()"
        ></profile-nav>
        <router-view
            v-if="profileRelationType!='block'"
            :user="user"
            :profilePhoto="profilePhoto"
            :profileRelationType="profileRelationType"
        ></router-view>
    </div>
</template>

<script>
import Header from "./Header.vue";
import ProfileNav from "./ProfileNav.vue";
import UpdateProfilePhoto from "./UpdateProfilePhoto.vue";

import axios from "axios";
export default {
    data() {
        return {
            id: this.$route.params.id,
            user: {},
            profilePhoto: "",
            screenOverlay: false,
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
                this.profilePhoto =
                    "http://carmeer.com/photo/" + event.photoName;
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
        "profile-nav": ProfileNav,
        "update-profile-photo": UpdateProfilePhoto
    },
    created: function() {
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
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token")
                    }
                }
            )
            .then(res => this.loadProfileRelationType(res.data));
    }
};
</script>
<style scoped>
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
