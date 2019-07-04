<template>
    <div>
        <div v-if="public">
            <post-privacy-dailog ic="fa-globe" :postPrivacy="postPrivacy"></post-privacy-dailog>
        </div>

        <div v-if="friends">
            <post-privacy-dailog ic="fa-user-friends" :postPrivacy="postPrivacy"></post-privacy-dailog>
        </div>

        <div v-if="private">
            <post-privacy-dailog ic="fa-lock" :postPrivacy="postPrivacy"></post-privacy-dailog>
        </div>

        <div v-if="custom">
            <post-privacy-dailog v-if="postPrivacy" ic="fa-star-of-life" :postPrivacy="postPrivacy"></post-privacy-dailog>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import PostPrivacyDailog from "./PostPrivacyDailog.vue";
export default {
    data() {
        return {
            public: false,
            private: false,
            friends: false,
            custom: false,
            postPrivacy: false
        };
    },
    components: {
        "post-privacy-dailog": PostPrivacyDailog
    },
    created: function() {
        axios({
            method: "get", //you can set what request you want to be
            url: "http://carmeer.com/api/default_privacy",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("access_token")
            }
        }).then(res => this.handlePostPrivacy(res.data));
    },

    methods: {
        handlePostPrivacy(data) {
            if (data.status == "public") {
                this.public = true;
            }
            if (data.status == "custom") {
                this.custom = true;
            }
            if (data.status == "private") {
                this.private = true;
            }
            if (data.status == "friends") {
                this.friends = true;
            }
            this.postPrivacy = this.data;
        }
    }
};
</script>

<style scoped>
</style>
