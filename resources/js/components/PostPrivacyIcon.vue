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
        changePostPrivacy(status, id_list) {
            this.$emit("postPrivacyUpdated", status, id_list);
        }
    }
};
</script>

<style scoped>
</style>
