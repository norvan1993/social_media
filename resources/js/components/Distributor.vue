<template>
    <div>
        <vue-header></vue-header>
        <slot></slot>
    </div>
</template>

<script>
import Header from "./Header.vue";
import { mapMutations } from "vuex";
export default {
    methods: {
        ...mapMutations(["vuexLogIn", "vuexLogOut"]),
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
    computed: {},
    components: {
        "vue-header": Header
    },
    created: function() {
        if (localStorage.getItem("access_token")) {
            this.vuexLogIn();
        } else {
            this.vuexLogOut();
        }
        this.checkAuthState();
    }
};
</script>

<style scoped>
</style>
