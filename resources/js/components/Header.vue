<template>
    <div>
        <auth-header v-if="auth"></auth-header>
        <guest-header v-if="guest"></guest-header>
    </div>
</template>

<script>
import AuthHeader from "./AuthHeader.vue";
import GuestHeader from "./GuestHeader.vue";
export default {
    data() {
        return {
            auth: false,
            guest: false
        };
    },
    created: function() {
        if (localStorage.getItem("access_token")) {
            this.auth = true;
        } else {
            this.guest = true;
        }
    },
    components: {
        "auth-header": AuthHeader,
        "guest-header": GuestHeader
    }
};
</script>

<style lang="scss" scoped>
</style>
