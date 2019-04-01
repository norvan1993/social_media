<template>
    <div class="imagesContainer">
        <div class="item" v-if="first">
            <div class="itemInner" v-for="first"></div>
        </div>
        <div class="item" v-if="second">
            <div class="itemInner" v-for="second"></div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["files"],
    data() {
        return {
            first: "",
            second: ""
        };
    },
    created: function() {
        this.setHorOrVer();
    },
    methods: {
        /************
         * distribution
         ***********/
        distribution() {
            var distrubtion = [];
            switch (this.files.length) {
                case 1:
                    distrubtion = [1, 0];
                    break;
                case 2:
                    distrubtion = [2, 0];
                    break;
                case 3:
                    distrubtion = [1, 2];
                    break;
                case 4:
                    if (this.dimension() == "square") {
                        distrubtion = [2, 2];
                    }
                    distrubtion = [1, 3];
                    break;
                default:
                    distrubtion = [2, 3];
                    break;
            }
            this.first = distrubtion[0];
            this.second = distrubtion[1];
        },
        /************
         * dimension
         ***********/
        dimension() {
            //select first image to check porporation
            img = new Image();
            img.src = this.files[0];
            //get dimensions
            if (img.width > img.height) {
                return "wide";
            }
            if (img.width < img.height) {
                return "tall";
            }
            if (img.width == img.height) {
                return "square";
            }
        },
        setHorOrVer() {
            if (this.dimension() == "tall") {
                //vertical

                //imagescontainer styles
                document.getElementsByClassName(
                    "imagesContainer"
                )[0].style.flexDirection = "row";
                //item styles
                var item = document.getElementsByClassName("item");
                for (var i = 0; i < item.length; i++) {
                    item[i].style.flexDirection = "column";
                    item[i].style.height = "100%";
                }
                //itemInner styles
                var itemInner = document.getElementsByClassName("itemInner");
                for (var j = 0; i < itemInner.length; j++) {
                    itemInner[j].style.width = "100%";
                }
            }
            //else horizantal

            //imagescontainer styles
            document.getElementsByClassName(
                "imagesContainer"
            )[0].style.flexDirection = "column";
            //item styles
            var item = document.getElementsByClassName("item");
            for (var i = 0; i < item.length; i++) {
                item[i].style.flexDirection = "row";
                item[i].style.width = "100%";
            }
            //itemInner styles
            var itemInner = document.getElementsByClassName("itemInner");
            for (var j = 0; i < itemInner.length; j++) {
                itemInner[j].style.height = "100%";
            }
        }
    }
};
</script>

<style scoped>
.imagesContainer {
    display: flex;
    /*flex-direction: column;*/
    width: 400px;
    height: 250px;
    background-color: red;
    border: solid 5px red;
}
.item {
    display: flex;
    /*flex-direction: row;*/
    flex-grow: 1;
    /*width: 100%;*/
    border: solid 2px yellow;
    background-color: blue;
}
.itemInner {
    flex-grow: 1;
    /*height: 100%;*/
    border: solid 2px green;
    background-color: black;
}
</style>
