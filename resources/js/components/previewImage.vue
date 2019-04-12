<template>
    <div
        v-if="photoIndex"
        style="background-color:rgba(0,0,0,0.5); position:fixed; top:0px; left:0px; width:100%; height:100%; z-index:3;"
    >
        <div
            style="background-position:center;background-repeat:no-repeat;background-size:contain;"
            class="imageBlock"
            :style="{backgroundImage:'url(http://carmeer.com/photo/'+files[photoIndex-1].file+')'}"
        >
            <div v-if="files[photoIndex]" @click="next()" class="next">></div>
            <div v-if="files[photoIndex-2]" @click="previous()" class="previous"><</div>
        </div>
        <div @click="closeImage()" class="closeImage">X</div>

        <media-intrection-box :user="user" :photoId="files[photoIndex-1].id"></media-intrection-box>
    </div>
</template>

<script>
import MediaInteractionBox from "./MediaInteractionBox.vue";
export default {
    props: ["photoIndex", "files", "user"],
    data() {
        return {};
    },
    components: {
        "media-intrection-box": MediaInteractionBox
    },
    methods: {
        /*********
         *closeImage
         *********/
        closeImage() {
            this.$emit("changePhotoIndex", 0);
        },
        /*********
         *next
         *********/
        next() {
            if (this.files[this.photoIndex]) {
                this.$emit("changePhotoIndex", this.photoIndex + 1);
            }
        },
        /*********
         *previous
         *********/
        previous() {
            if (this.files[this.photoIndex - 2]) {
                this.$emit("changePhotoIndex", this.photoIndex - 1);
            }
        }
    }
};
</script>

<style scoped>
@media only screen and (max-width: 575px) {
    .imageBlock {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: black;
    }
}
@media only screen and (min-width: 576px) {
    .imageBlock {
        position: absolute;
        left: 5%;
        right: 40%;
        bottom: 10%;
        top: 10%;
        background-color: black;
    }
}
.closeImage {
    position: absolute;
    top: 10px;
    left: 10px;
    cursor: pointer;
    z-index: 2;
    color: rgba(255, 255, 255, 0.7);
    font-size: 36px;
}
.next {
    position: absolute;
    bottom: 40%;
    right: 5px;
    cursor: pointer;
    z-index: 2;
    color: rgba(255, 255, 255, 0.3);
    font-size: 72px;
}
.previous {
    position: absolute;
    bottom: 40%;
    left: 5px;
    cursor: pointer;
    z-index: 2;
    color: rgba(255, 255, 255, 0.3);
    font-size: 72px;
}
</style>
