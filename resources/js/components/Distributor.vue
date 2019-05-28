<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
import { mapMutations } from "vuex";
export default {
    methods: {
        ...mapMutations(["vuexLogIn", "vuexLogOut"])
    },

    created: function() {
        if (localStorage.getItem("access_token")) {
            this.vuexLogIn();
        } else {
            this.vuexLogOut();
        }
    }
};
</script>

<style scoped>
</style>
