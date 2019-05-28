<template>
    <div>
        <auth-header v-if="auth"></auth-header>
        <guest-header v-else></guest-header>
    </div>
</template>

<script>
import AuthHeader from "./AuthHeader.vue";
import GuestHeader from "./GuestHeader.vue";
import { mapState } from "vuex";
export default {
    data() {
        return {};
    },
    computed: {
        ...mapState(["auth", "guest"])
    },
    components: {
        "auth-header": AuthHeader,
        "guest-header": GuestHeader
    }
};
</script>

<style  scoped>
</style>
