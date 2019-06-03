<template>
    <div class="updateProfilePhotoBoxContainer">
        <div class="row justify-content-center" style="margin-top:100px;">
            <div class="bg-white col-sm-6" style="height:400px;" align="center">
                <div class="row justify-content-center">
                    <img :src="profilePhotoPreview" class="profileImgUpdate mt-2">
                </div>
                <div class="row justify-content-center">
                    <div class="col-xs-8 col-sm-6 col-md-5 mt-2">
                        <input
                            @change="previewProfilePhoto()"
                            type="file"
                            ref="profilePhotoSelector"
                            hidden
                        >
                        <button
                            class="btn btn-primary btn-block"
                            @click="$refs.profilePhotoSelector.click()"
                        >choose photo</button>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xs-8 col-sm-6 col-md-5 mt-2">
                        <button
                            class="btn btn-primary btn-block"
                            @click="updateProfilePhoto()"
                        >update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["profilePhoto"],
    data() {
        return {
            profilePhotoPreview: "",
            profilePhotoFile: ""
        };
    },
    methods: {
        previewProfilePhoto() {
            var file = this.$refs.profilePhotoSelector.files[0];
            this.profilePhotoFile = file;
            this.profilePhotoPreview = URL.createObjectURL(file);
        },
        updateProfilePhoto() {
            var form = new FormData();
            if (this.profilePhotoPreview == this.profilePhoto) {
                alert("the photo has not been changed");
                return 0;
            }
            form.append("_method", "PUT");
            form.append("photo", this.profilePhotoFile);

            axios({
                method: "post", //you can set what request you want to be
                url:
                    "http://carmeer.com/api/users/" +
                    localStorage.getItem("auth_id") +
                    "/update_profile_photo",
                data: form,
                headers: {
                    Authorization:
                        "Bearer " + localStorage.getItem("access_token"),
                    "Content-Type": "multipart/form-data"
                }
            })
                .then(res => this.handleProfilePhotoUpdate(res.data))
                .catch(error => alert(JSON.stringify(error.response)));
        },
        handleProfilePhotoUpdate(data) {
            this.$emit("update", data);
        }
    },
    created: function() {
        this.profilePhotoPreview = this.profilePhoto;
    }
};
</script>

<style  scoped>
.updateProfilePhotoBoxContainer {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 4;
}
.profileImgUpdate {
    width: 250px;
    height: 250px;
    border: 2px solid white;
    box-shadow: 0px 0px 10px 0.5px rgb(159, 159, 159);
}
</style>
