<template>
    <div>
        <vue-header></vue-header>
        <slot></slot>
    </div>
</template>

<script>
import Header from "./Header.vue";
export default {
    methods: {
        checkAuthState() {
            if (localStorage.getItem("access_token")) {
                if (
                    window.location.href == "http://carmeer.com" ||
                    window.location.href == "http://carmeer.com/login" ||
                    window.location.href == "http://carmeer.com/register"
                ) {
                    this.$router.push({ path: "/home" });
                }
            } else {
                if (
                    window.location.href != "http://carmeer.com" &&
                    window.location.href != "http://carmeer.com/login" &&
                    window.location.href != "http://carmeer.com/register"
                ) {
                    this.$router.push({ path: "/" });
                }
            }
        }
    },
    components: {
        "vue-header": Header
    }
};
</script>

<style scoped>
</style>
