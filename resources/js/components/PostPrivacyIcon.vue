<template>
    <div>
        <div v-if="postPrivacy">
            <post-privacy-dailog
                :postPrivacy="postPrivacy"
                @postPrivacyUpdated="changePostPrivacy($event)"
            ></post-privacy-dailog>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import PostPrivacyDailog from "./PostPrivacyDailog.vue";
export default {
    props: ["postPrivacy"],
    data() {
        return {};
    },
    components: {
        "post-privacy-dailog": PostPrivacyDailog
    },

    methods: {
        changePostPrivacy(postPrivacyUpdated) {
            this.$emit("postPrivacyUpdated", postPrivacyUpdated);
        }
    }
};
</script>

<style scoped>
</style>
