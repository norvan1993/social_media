<template>
    <div>
        <!--header-->
        <vue-header></vue-header>
        <div v-if="user">
            <div class="profileTemp p-3 border" align="center">
                <img :src="profilePhoto" class="profileImg">

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
        <router-view :user="user"></router-view>
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
            profilePhoto: ""
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
        }
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
</style>
