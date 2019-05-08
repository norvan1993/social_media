<template>
    <div>
        <i class="fas fa-globe"></i>
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
            console.log(data);
        }
    }
};
</script>

<style scoped>
</style>
