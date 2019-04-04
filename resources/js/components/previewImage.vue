<template>
    <div
        v-if="photoIndex"
        style="background-color:red; position:fixed; top:0px; left:0px; width:100%; height:100%; z-index:3;"
    >
        <div
            style=" position:absolute; top:0px; left:0px; width:100%; height:100%; background-position:center;background-repeat:no-repeat;background-size:contain;"
            :style="{backgroundImage:'url(http://carmeer.com/photo/'+files[photoIndex-1].file+')'}"
        ></div>
        <div
            @click="closeImage()"
            style="position:absolute; top:0px; left:0px;cursor:pointer; z-index:2;"
        >X</div>
        <div
            v-if="files[photoIndex]"
            @click="next()"
            style="position:absolute; bottom:0px; right:0px;cursor:pointer; z-index:2;"
        >Next</div>
        <div
            v-if="files[photoIndex-2]"
            @click="previous()"
            style="position:absolute; bottom:0px; left:0px;cursor:pointer; z-index:2;"
        >Previous</div>
    </div>
</template>

<script>
export default {
    props: ["photoIndex", "files"],
    data() {
        return {};
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
</style>
