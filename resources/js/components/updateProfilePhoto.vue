<template>
    <div v-if="screenOverlay" class="updateProfilePhotoBoxContainer">
        <div class="row justify-content-center" style="margin-top:100px;">
            <div class="bg-white col-sm-6" style="height:400px;" align="center">
                <div class="row justify-content-center">
                    <img :src="profilePhoto" class="profileImgUpdate mt-2">
                </div>
                <div class="row justify-content-center">
                    <div class="col-xs-8 col-sm-6 col-md-5 mt-2">
                        <input
                            @change="appendPhoto()"
                            type="file"
                            ref="profilePhotoSelector"
                            hidden
                        >
                        <button class="btn btn-primary btn-block">choose photo</button>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xs-8 col-sm-6 col-md-5 mt-2">
                        <button class="btn btn-primary btn-block">update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
</style>
