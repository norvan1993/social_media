<template>
    <div>
        <div v-if="public">
            <i class="fas fa-globe"></i>
        </div>

        <div v-if="friends">
            <i class="fas fa-user-friends"></i>
        </div>

        <div v-if="private">
            <i class="fas fa-lock"></i>
        </div>

        <div v-if="custom">
            <i class="fas fa-star-of-life"></i>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            public: false,
            private: false,
            friends: false,
            custom: false
        };
    },
    created: function() {
        axios({
            method: "get", //you can set what request you want to be
            url: "http://carmeer.com/api/default_privacy",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("access_token")
            }
        })
            .then(res => this.handlePost(res.data))
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
