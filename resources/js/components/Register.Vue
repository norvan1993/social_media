<template>
    <div>
        <!--header-->
        <vue-header></vue-header>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>

                        <div class="card-body">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input
                                        id="name"
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        required
                                        autofocus
                                        v-model="name"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="email"
                                    class="col-md-4 col-form-label text-md-right"
                                >Email-Address</label>

                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        required
                                        v-model="email"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="password"
                                    class="col-md-4 col-form-label text-md-right"
                                >Password</label>

                                <div class="col-md-6">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        required
                                        v-model="password"
                                    >
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button class="btn btn-primary" @click="register()">Register</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from "./Header.vue";
import { mapMutations } from "vuex";
export default {
    data() {
        return {
            name: "",
            email: "",
            password: ""
        };
    },
    components: {
        "vue-header": Header
    },
    methods: {
        ...mapMutations(["vuexLogIn"]),
        register() {
            var form = new FormData();
            form.append("name", this.name);
            form.append("email", this.email);
            form.append("password", this.password);
            axios
                .post("http://carmeer.com/api/register", form, {
                    headers: {
                        "content-type": "multipart/form-data"
                    }
                })
                .then(res => this.handleToken(res.data));
        },
        handleToken(data) {
            localStorage.setItem("access_token", data.access_token);
            this.getAuthIdFromServerAndSaveInStorage();
        },
        getAuthIdFromServerAndSaveInStorage() {
            axios
                .get("http://carmeer.com/api/auth", {
                    headers: {
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token")
                    }
                })
                .then(res => this.setAuthIdInLocalStorage(res.data));
        },
        setAuthIdInLocalStorage(data) {
            localStorage.setItem("auth_id", data);
            this.vuexLogIn();
            this.$router.push({ path: "/home" });
        }
    }
};
</script>

<style scoped>
</style>
