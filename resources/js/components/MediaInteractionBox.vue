<template>
    <div class="mediaBox d-none d-sm-block">
        <!------------photo owner name +profile pic------------>
        <div class="row">
            <div class="col-12">
                <img :src="'http://carmeer.com/photo/'+user.file" class="profileImg ml-2 my-2">
                <p class="ownerName d-inline-block ml-2 my-2 font-weight-bold">{{user.name}}</p>
            </div>
        </div>
        <!------------photo description if exist------------>
        <div class="row">
            <div class="col-12">
                <p
                    v-if="showDescription"
                    class="ownerName d-inline-block ml-2 my-2 font-weight-normal"
                >{{description.body}}</p>
            </div>
        </div>
        <!------------add description button || description text area ||edit desciption ||delete description------------>
        <div class="row">
            <div class="col-12">
                <button
                    v-if="addDescriptionButton"
                    @click="showDescriptionInput()"
                    class="btn btn-primary mt-3"
                >Add Description</button>
                <button
                    v-if="editDescriptionButton"
                    @click="showDescriptionInput()"
                    class="btn btn-success mt-3 ml-1"
                >edit Description</button>
                <button
                    v-if="deleteDescriptionButton"
                    @click="deleteDescription()"
                    class="btn btn-danger mt-3"
                >delete Description</button>
                <textarea
                    v-if=" descriptionInput"
                    placeholder="write something"
                    rows="4"
                    class="d-block ml-3 border"
                    style="width:70%; resize:none;"
                    v-model="body"
                ></textarea>
            </div>
        </div>
        <!------------create description button if exist----------->
        <div class="row" v-if="descriptionInput">
            <div class="col-12">
                <button @click="createDescription()" class="btn btn-primary">create Description</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["user", "photoId"],
    data() {
        return {
            description: null,
            addDescriptionButton: false,
            descriptionInput: false,
            body: "",
            showDescription: true,
            isPhotoOwner: false,
            editDescriptionButton: false,
            deleteDescriptionButton: false
        };
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
    },
    watch: {
        photoId: function() {
            this.description = null;
            this.addDescriptionButton = false;
            this.descriptionInput = false;
            this.body = "";
            if (localStorage.getItem("auth_id") == this.user.id) {
                this.isPhotoOwner = true;
            } else {
                this.isPhotoOwner = false;
            }

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
    },
    methods: {
        /***********************************************
         * setDescription
         **********************************************/
        setDescription(data) {
            this.descriptionInput = false;
            this.showDescription = true;
            this.description = data;
            this.body = data.body;
            if (
                (this.description == "" || this.description == null) &&
                this.isPhotoOwner
            ) {
                this.addDescriptionButton = true;
            }
            if (
                this.description != "" &&
                this.description != null &&
                this.isPhotoOwner
            ) {
                this.editDescriptionButton = true;
                this.deleteDescriptionButton = true;
            }
        },
        /***********************************************
         * showDescriptionInput
         **********************************************/
        showDescriptionInput() {
            this.addDescriptionButton = false;
            this.editDescriptionButton = false;
            this.deleteDescriptionButton = false;
            this.showDescription = false;
            this.descriptionInput = true;
        },
        /***********************************************
         * createDescription
         **********************************************/
        createDescription() {
            var form = new FormData();
            form.append("body", this.body);
            form.append("photo_id", this.photoId);
            axios
                .post("http://carmeer.com/api/descriptions", form, {
                    headers: {
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token"),
                        "content-type": "multipart/form-data"
                    }
                })
                .then(res => this.setDescription(res.data));
        }
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
