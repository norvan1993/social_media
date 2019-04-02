<template>
    <div class="card mt-3">
        <div class="card-header">
            <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg">
            <span class="ml-2" style="cursor:pointer;">{{user.name}}</span>
        </div>
        <div class="card-body">
            <h4>
                <p>{{post.title}}</p>
                <hr>
            </h4>
            <div class="card-text">
                <p>{{post.body}}</p>
                <hr>
            </div>
            <preview-images v-if="photos" :files="photos"></preview-images>
        </div>
    </div>
</template>

<script>
import PreviewImages from "./PreviewImages.vue";
export default {
    props: ["user", "post"],
    data() {
        return {
            photos: null
        };
    },
    methods: {
        setPhotos(photos) {
            this.photos = photos;
        }
    },
    components: {
        "preview-images": PreviewImages
    },
    created: function() {
        axios
            .get("http://carmeer.com/api/posts/" + this.post.id + "/photos", {
                headers: {
                    Authorization:
                        "Bearer " + localStorage.getItem("access_token")
                }
            })
            .then(res => this.setPhotos(res.data));
    }
};
</script>

<style scoped>
.profileImg {
    width: 40px;
    height: 40px;
    border: 1px solid black;
}
</style>

