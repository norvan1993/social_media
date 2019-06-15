<template>
    <div v-if="user">
        <div class="row p-3 border">
            <div class="col-sm-6 p-3" align="center">
                <div class="profilePhoto float-left" @click="openScreenOverlay()">
                    <div style="width:100%; height:100%; position:relative;">
                        <img :src="profilePhoto" class="profileImg">
                        <div class="profilePhotoOverlay"></div>
                    </div>
                </div>
                <p class="userName h3 float-left ml-3">
                    {{user.name}}
                    <br>
                    <v-btn
                        color="success"
                        v-if="profileRelationType=='not_friend'"
                        @click="sendFriendRequest()"
                    >send friend request</v-btn>

                    <v-btn
                        color="success"
                        v-if="profileRelationType=='sender'"
                        @click="respondTofriendRequest()"
                    >respond</v-btn>
                    <v-btn
                        color="error"
                        v-if="profileRelationType=='receiver'"
                        @click="cancelFriendRequest()"
                    >reomove friend request</v-btn>
                </p>
            </div>
        </div>

        <div class="row p-3 border justify-content-center">
            <router-link class="p-3" :to="'/profile/'+this.$route.params.id" exact>timeline</router-link>
            <hr>
            <router-link
                class="p-3"
                :to="'/profile/'+this.$route.params.id+'/friends'"
                exact
            >friends</router-link>
            <hr>
            <router-link class="p-3" :to="'/profile/'+this.$route.params.id+'/photos'" exact>photos</router-link>
            <hr>
            <router-link class="p-3" :to="'/profile/'+this.$route.params.id+'/about'" exact>about</router-link>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "id",
        "user",
        "profileRelationType",
        "screenOverlay",
        "profilePhoto"
    ],

    methods: {
        openScreenOverlay() {
            this.$emit("openScreenOverlay");
        },
        sendFriendRequest() {
            var form = new FormData();
            form.append("receiver_id", this.user.id);

            axios({
                method: "post", //you can set what request you want to be
                url: "http://carmeer.com/api/friendship/send",
                data: form,
                headers: {
                    Authorization:
                        "Bearer " + localStorage.getItem("access_token"),
                    "Content-Type": "multipart/form-data"
                }
            })
                .then(res => this.handleFriendRequestSending(res.data[0]))
                .catch(error => alert(JSON.stringify(error.response)));
        },
        handleFriendRequestSending() {
            alert("success");
        },
        cancelFriendRequest() {
            var form = new FormData();
            form.append("_method", "DELETE");
            form.append("receiver_id", this.user.id);

            axios({
                method: "post", //you can set what request you want to be
                url: "http://carmeer.com/api/friendship/cancel",
                data: form,
                headers: {
                    Authorization:
                        "Bearer " + localStorage.getItem("access_token"),
                    "Content-Type": "multipart/form-data"
                }
            })
                .then(res => this.handleFriendRequestCanceling(res.data[0]))
                .catch(error => alert(JSON.stringify(error.response)));
        },
        handleFriendRequestCanceling() {
            alert("success");
        }
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
