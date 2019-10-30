<template>
    <div>
        <div v-if="postPrivacy">
            <post-privacy-dailog :postPrivacy="postPrivacy"></post-privacy-dailog>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import PostPrivacyDailog from "./PostPrivacyDailog.vue";
export default {
    data() {
        return {
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
        }).then(res => (this.postPrivacy = res.data));
    },

    methods: {
        /*
        handlePostPrivacy(data) {
            this.postPrivacy = data;
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
        }
        */
    }
};
</script>

<style scoped>
</style>
