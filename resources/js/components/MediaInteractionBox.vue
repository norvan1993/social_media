<template>
    <div class="mediaBox hidden-xs-only grey lighten-3">
        <!------------photo owner name +profile pic------------>
        <div class="row">
            <div class="col-12">
                <img :src="profilePhoto" class="profileImg ml-2 my-2">
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
                    @click="showDescriptionInput('store')"
                    class="btn btn-primary mt-3"
                >Add Description</button>
                <button
                    v-if="editDescriptionButton"
                    @click="showDescriptionInput('update')"
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
                <button
                    v-if="update"
                    @click="updateDescription()"
                    class="btn btn-primary"
                >update Description</button>
                <button
                    v-if="store"
                    @click="createDescription()"
                    class="btn btn-primary"
                >create Description</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["user", "photoId", "profilePhoto"],
    data() {
        return {
            description: null,
            addDescriptionButton: false,
            descriptionInput: false,
            body: "",
            showDescription: true,
            isPhotoOwner: false,
            editDescriptionButton: false,
            deleteDescriptionButton: false,
            store: false,
            update: false
        };
    },
    created: function() {
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
    },
    watch: {
        photoId: function() {
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
        },
        /***********************************************
         * updateDescription
         **********************************************/
        updateDescription() {
            var form = new FormData();
            form.append("_method", "PUT");
            form.append("body", this.body);
            form.append("photo_id", this.photoId);
            axios
                .post(
                    "http://carmeer.com/api/descriptions/" +
                        this.description.id,
                    form,
                    {
                        headers: {
                            Authorization:
                                "Bearer " +
                                localStorage.getItem("access_token"),
                            "content-type": "multipart/form-data"
                        }
                    }
                )
                .then(res => this.setDescription(res.data));
        },
        /***********************************************
         * delete Description
         **********************************************/
        deleteDescription() {
            var form = new FormData();
            form.append("_method", "DELETE");
            axios
                .post(
                    "http://carmeer.com/api/descriptions/" +
                        this.description.id,
                    form,
                    {
                        headers: {
                            Authorization:
                                "Bearer " +
                                localStorage.getItem("access_token"),
                            "content-type": "multipart/form-data"
                        }
                    }
                )
                .then(res => this.setDescription(res.data));
        },
        /***********************************************
         * setDescription
         **********************************************/
        setDescription(data) {
            this.resetDescriptionAndButtons();
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
        showDescriptionInput($status) {
            this.addDescriptionButton = false;
            this.editDescriptionButton = false;
            this.deleteDescriptionButton = false;
            this.showDescription = false;
            this.descriptionInput = true;
            if ($status == "store") {
                this.store = true;
                this.update = false;
            } else {
                this.store = false;
                this.update = true;
            }
        },
        /************************************************
         *resetDescriptionAndButtons
         ***********************************************/
        resetDescriptionAndButtons() {
            this.descriptionInput = false;
            this.store = false;
            this.update = false;
            this.description = null;
            this.body = "";
            this.addDescriptionButton = false;
            this.editDescriptionButton = false;
            this.deleteDescriptionButton = false;
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
