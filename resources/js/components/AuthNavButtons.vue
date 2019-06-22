<template>
    <div>
        <!--log out button on md screens and larger-->
        <v-btn
            @click="logOut()"
            class="indigo darken-4 white--text float-right hidden-sm-and-down"
        >log out</v-btn>
        <!--bars icon in small screens-->
        <div class="hidden-md-and-up p-3 indigo darken-3 fab">
            <v-icon class="white--text">fa-bars</v-icon>
        </div>
    </div>
</template>

<script>
import { mapMutations } from "vuex";
export default {
    methods: {
        ...mapMutations(["vuexLogOut"]),
        logOut() {
            axios
                .get("http://carmeer.com/api/logout", {
                    headers: {
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token")
                    }
                })
                .then(res => this.handleToken(res.data));
        },
        handleToken(data) {
            localStorage.clear();
            this.vuexLogOut();
            this.$router.push({ path: "/" });
        }
    }
};
</script>

<style scoped>
</style>
