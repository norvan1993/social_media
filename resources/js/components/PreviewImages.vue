<template>
  <div class="imagesContainer">
    <div class="item" v-if="first">
      <div class="itemInner" v-for="firstRowImg in first" ref></div>
    </div>
    <div class="item" v-if="second">
      <div class="itemInner" v-for="secondRowImg in second"></div>
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
    distribution(dimension) {
      let distrubtion = [];
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
          if (dimension == "square") {
            distrubtion = [2, 2];
          }
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
      alert(dimension);
      let container = document.getElementsByClassName("imagesContainer")[0];
      container.style.backgroundColor = "blue";
      if (dimension == "tall") {
        //vertical
        //imagescontainer styles

        container.style.flexDirection = "row";

        //item styles
        let item = document.getElementsByClassName("item");
        for (var i = 0; i < item.length; i++) {
          item[i].style.flexDirection = "column";
          item[i].style.height = "100%";
        }
        //itemInner styles
        let itemInner = document.getElementsByClassName("itemInner");
        for (var j = 0; i < itemInner.length; j++) {
          itemInner[j].style.width = "100%";
        }
        return true;
      }
      //else horizantal

      //imagescontainer styles
      container.style.flexDirection = "column";
      container.style.innerHtml = "";
      //item styles
      let item = document.getElementsByClassName("item");
      for (let i = 0; i < item.length; i++) {
        item[i].style.flexDirection = "row";
        item[i].style.width = "100%";
      }
      //itemInner styles
      var itemInner = document.getElementsByClassName("itemInner");
      for (var j = 0; i < itemInner.length; j++) {
        itemInner[j].style.height = "100%";
      }
    }
  },
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
.imagesContainer {
  display: flex;
  width: 400px;
  height: 250px;
  background-color: red;
  border: solid 5px red;
}
.item {
  display: flex;
  flex-grow: 1;
  border: solid 2px yellow;
  background-color: blue;
}
.itemInner {
  flex-grow: 1;
  border: solid 2px green;
  background-color: black;
}
</style>
