<template>
    <div>
        <div v-if="postPrivacy">
            <post-privacy-dailog :postPrivacy="postPrivacy"></post-privacy-dailog>
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

    methods: {}
};
</script>

<style scoped>
</style>
