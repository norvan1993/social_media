<template>
    <div class="row justify-content-center">
        <div class="col-sm-5">
            <create-post
                v-if="profileRelationType=='owner'"
                :user="user"
                :profilePhoto="profilePhoto"
            ></create-post>
            <div v-for="post in postData.data">
                <preview-post :user="user" :post="post" :profilePhoto="profilePhoto"></preview-post>
            </div>
        </div>
    </div>
</template>

<script>
import CreatePost from "./CreatePost.vue";
import PreviewPost from "./PreviewPost.vue";
import axios from "axios";
export default {
    props: ["user", "profilePhoto", "profileRelationType"],
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
