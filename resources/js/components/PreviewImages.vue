<template>
    <div class="imagesContainer" :style="{'flex-direction':containerFlexDirection}">
        <div
            class="item"
            v-if="first"
            :style="{flexDirection:itemFlexDirection,width:itemWidth,height:itemHeight}"
        >
            <div
                @click="openImage(firstRowImg)"
                class="itemInner"
                v-for="firstRowImg in first"
                style="background-position:center;background-repeat:no-repeat;background-size:cover;"
                :style="{'width':itemInnerWidth,'height':itemInnerHeight,backgroundImage:'url(http://carmeer.com/photo/'+files[firstRowImg-1].file+')'}"
            ></div>
        </div>
        <div
            class="item"
            v-if="second"
            :style="{'flex-direction':itemFlexDirection,'width':itemWidth,'height':itemHeight}"
        >
            <div
                @click="openImage(first+secondRowImg)"
                class="itemInner"
                v-for="secondRowImg in second"
                style="background-position:center;background-repeat:no-repeat;background-size:cover;"
                :style="{'width':itemInnerWidth,'height':itemInnerHeight,backgroundImage:'url(http://carmeer.com/photo/'+files[first+secondRowImg-1].file+')'}"
            ></div>
        </div>
        <preview-image
            :files="files"
            :photoIndex="photoIndex"
            @changePhotoIndex="photoIndex=$event"
        ></preview-image>
    </div>
</template>
<script>
import PreviewImage from "./PreviewImage.vue";
export default {
    props: ["files"],
    data() {
        return {
            first: "",
            second: "",
            containerFlexDirection: "",
            itemFlexDirection: "",
            itemHeight: "",
            itemWidth: "",
            itemInnerWidth: "",
            itemInnerHeight: "",
            photoIndex: 0
        };
    },
    components: {
        "preview-image": PreviewImage
    },
    methods: {
        /************
         * dimension
         ***********/
        dimension(callback) {
            //select first image to check porporation
            let img = new Image();
            img.onload = function() {
                //get dimensions
                let dimension = "";
                if (img.width > img.height) {
                    dimension = "wide";
                } else if (img.width < img.height) {
                    dimension = "tall";
                } else if (img.width == img.height) {
                    dimension = "square";
                }
                callback(dimension);
            };
            img.src = "http://carmeer.com/photo/" + this.files[0].file;
        },
        /************
         * distribution
         ***********/
        distribution(dimension) {
            let distrubtion = [];
            switch (this.files.length) {
                case 1:
                    distrubtion = [1, 0];
                    break;
                case 2:
                    distrubtion = [1, 1];
                    break;
                case 3:
                    distrubtion = [1, 2];
                    break;
                case 4:
                    distrubtion = [1, 3];
                    break;
                default:
                    distrubtion = [2, 3];
            }

            this.first = distrubtion[0];
            this.second = distrubtion[1];
        },
        /************
         * setHorOrVer
         ***********/
        setHorOrVer(dimension) {
            if (dimension == "tall") {
                //vertical
                //imagescontainer styles
                this.containerFlexDirection = "row";
                //item styles
                this.itemFlexDirection = "column";
                this.itemHeight = "100%";
                //itemInner styles
                this.itemInnerWidth = "100%";
                return true;
            }
            //else horizantal
            //imagescontainer styles
            this.containerFlexDirection = "column";
            //item styles
            this.itemFlexDirection = "row";
            this.itemWidth = "100%";
            //itemInner styles
            this.itemInnerHeight = "100%";
        },
        /*********
         *openImage
         *********/
        openImage(photoIndex) {
            this.photoIndex = photoIndex;
        }
    },
    /************
     * created
     ***********/
    created: function() {
        let obj = this;
        this.dimension(function(dimension) {
            obj.distribution(dimension);
            obj.setHorOrVer(dimension);
        });
    }
};
</script>

<style scoped>
@media only screen and (max-width: 575px) {
    .imagesContainer {
        display: flex;
        width: 100%;
        height: 90vw;
        color: white;
    }
}
@media only screen and (min-width: 576px) {
    .imagesContainer {
        display: flex;
        width: 100%;
        height: 35vmax;
        color: white;
    }
}
.item {
    display: flex;
    flex-grow: 1;
}
.itemInner {
    flex-grow: 1;
    border: solid 1px white;
    cursor: pointer;
}
</style>
