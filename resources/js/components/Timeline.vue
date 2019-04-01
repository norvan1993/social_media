<template>
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <create-post :user="user" :csrf="csrf"></create-post>
            <preview-post :post="post"></preview-post>
        </div>
    </div>
</template>

<script>
import CreatePost from "./CreatePost.vue";
import PreviewPost from "./PreviewPost.vue";
import axios from "axios";
export default {
    props: ["csrf"],
    data() {
        return {
            post: ""
        };
    },
    props: ["user"],
    components: {
        "create-post": CreatePost,
        "preview-post": PreviewPost
    },
    created: function() {
        //get user infos
        axios
            .get(
                "http://carmeer.com/api/users/" +
                    this.$route.params.id +
                    "/posts",
                null,
                {
                    headers: {
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token"),
                        "content-type": "multipart/form-data"
                    }
                }
            )
            .then(res => console.log(res.data));
    }
};
</script>

<style scoped>
</style>
