<template>
    <div>
        <div v-if="public">
            <post-privacy-dailog ic="fa-globe"></post-privacy-dailog>
        </div>

        <div v-if="friends">
            <post-privacy-dailog ic="fa-user-friends"></post-privacy-dailog>
        </div>

        <div v-if="private">
            <post-privacy-dailog ic="fa-lock"></post-privacy-dailog>
        </div>

        <div v-if="custom">
            <post-privacy-dailog ic="fa-star-of-life"></post-privacy-dailog>
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
            custom: false
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
        })
            .then(res => this.handlePostPrivacy(res.data))
            .catch(error => alert(JSON.stringify(error.response)));
    },

    methods: {
        handlePost(data) {
            if (data.status == "public") {
                this.public = true;
            }
        }
    }
};
</script>

<style scoped>
</style>
