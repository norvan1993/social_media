<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
export default {
    methods: {
        checkAuthState() {
            if (localStorage.getItem("access_token")) {
                if (
                    window.location.href == "welcome" ||
                    window.location.href == "login" ||
                    window.location.href == "register"
                ) {
                    this.$router.push({ path: "/home" });
                }
            } else {
                if (
                    window.location.href != "welcome" ||
                    window.location.href != "login" ||
                    window.location.href != "register"
                ) {
                    this.$router.push({ path: "/" });
                }
            }
        }
    }
};
</script>

<style scoped>
</style>
