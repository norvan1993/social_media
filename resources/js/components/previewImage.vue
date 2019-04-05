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

        <div class="mediaBox d-none d-sm-block">
            <div class="row">
                <div class="col-12">
                    <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg ml-2 my-2">
                    <p class="ownerName d-inline-block ml-2 my-2 font-weight-bold">{{user.name}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p class="ownerName d-inline-block ml-2 my-2 font-weight-normal">some text here</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["photoIndex", "files", "user"],
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
.mediaBox {
    position: absolute;
    left: 60%;
    right: 5%;
    bottom: 10%;
    top: 10%;
    background-color: white;
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
.profileImg {
    width: 50px;
    height: 50px;
    border: 1px solid black;
    display: inline-block;
}

.ownerName {
    color: black;
    position: relative;
    top: 17px;
    text-transform: capitalize;
}
</style>
