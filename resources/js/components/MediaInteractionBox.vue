<template>
    <div class="mediaBox d-none d-sm-block">
        <div class="row">
            <div class="col-12">
                <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg ml-2 my-2">
                <p class="ownerName d-inline-block ml-2 my-2 font-weight-bold">{{user.name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="ownerName d-inline-block ml-2 my-2 font-weight-normal">{{description}}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["user", "photoId"],
    data() {
        return {
            description: null
        };
    },
    methods: {
        setDescription(data) {
            console.log(data);
            //this.description = data;
        }
    },
    created: function() {
        axios
            .get(
                "http://carmeer.com/api/photos/" +
                    this.photoId +
                    "/description",
                {
                    headers: {
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token")
                    }
                }
            )
            .then(res => this.setDescription(res.data));
    }
};
</script>

<style scoped>
.mediaBox {
    position: absolute;
    left: 60%;
    right: 5%;
    bottom: 10%;
    top: 10%;
    background-color: white;
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
