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
    </div>
</template>
<script>
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

    created: function() {
        this.setHorOrVer();
    },
    methods: {
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
        openImage(photoIndex) {
            this.photoIndex = photoIndex;
        },
        closeImage(photoIndex) {
            this.photoIndex = photoIndex;
        },
        next() {
            if (this.files[this.photoIndex]) {
                this.photoIndex++;
            }
        },
        previous() {
            if (this.files[this.photoIndex - 2]) {
                this.photoIndex--;
            }
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
