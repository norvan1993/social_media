<template>
    <div class="row justify-content-center">
        <div class="col-sm-5">
            <create-post :user="user" :csrf="csrf"></create-post>
            <div v-for="post in postData.data">
                <preview-post :user="user" :post="post"></preview-post>
            </div>
        </div>
    </div>
</template>

<script>
import CreatePost from "./CreatePost.vue";
import PreviewPost from "./PreviewPost.vue";
import axios from "axios";
export default {
    props: ["csrf", "user"],
    data() {
        return {
            postData: ""
        };
    },

    components: {
        "create-post": CreatePost,
        "preview-post": PreviewPost
    },
    methods: {
        setPostData(postData) {
            this.postData = postData;
        }
    },
    created: function() {
        //get user posts
        axios
            .get(
                "http://carmeer.com/api/users/" +
                    this.$route.params.id +
                    "/posts",
                {
                    headers: {
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token")
                    }
                }
            )
            .then(res => this.setPostData(res.data));
    }
};
</script>

<style scoped>
</style>
