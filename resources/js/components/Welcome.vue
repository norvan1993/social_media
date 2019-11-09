<template>
    <div>
        <!--header-->
        <vue-header></vue-header>
        <div>
            <!--new row-->
            <div dark class="row mt-1 justify-content-center align-items-center black darken-3">
                <p
                    class="my-auto d-inline-block align-middle p-2 white--text"
                >why should you choose us</p>
            </div>

            <!--new row-->
            <div class="row m-3 justify-content-center">
                <!--find new friends card-->
                <div class="card col-md-2 m-3" style="width: 18rem;">
                    <img class="card-img-top" src="/svg/icons/findFriends.svg" alt="Card image cap" />
                    <div class="card-body">
                        <p class="card-text">find new friends</p>
                    </div>
                </div>
                <!--markets card-->
                <div class="card col-md-2 m-3" style="width: 18rem;">
                    <img class="card-img-top" src="/svg/icons/basket.svg" alt="Card image cap" />
                    <div class="card-body">
                        <p class="card-text">browse markets nearby</p>
                    </div>
                </div>
                <!--read articles-->
                <div class="card col-md-2 m-3" style="width: 18rem;">
                    <img
                        class="card-img-top"
                        src="/svg/icons/readArticles.svg"
                        alt="Card image cap"
                    />
                    <div class="card-body">
                        <p class="card-text">find new articles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from "./Header.vue";
import { mapState } from "vuex";
import { mapMutations } from "vuex";
export default {
    data() {
        return {};
    },

    components: {
        "vue-header": Header
    },
    methods: {}
};
</script>

<style scoped>
</style>
