<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">LogIn</div>

                    <div class="card-body">
                        <form @submit.prevent="login()">
                            <div class="form-group row">
                                <label
                                    for="email"
                                    class="col-md-4 col-form-label text-md-right"
                                >Email-Address</label>

                                <div class="col-md-6">
                                    <input
                                        name="email"
                                        type="email"
                                        class="form-control"
                                        required
                                        autofocus
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
                                        name="password"
                                        type="password"
                                        class="form-control"
                                        required
                                        v-model="password"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4"></div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">LogIn</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapMutations } from "vuex";
export default {
    data() {
        return {
            email: "",
            password: ""
        };
    },
    methods: {
        ...mapMutations(["vuexLogIn"]),
        login() {
            var form = new FormData();
            form.append("email", this.email);
            form.append("password", this.password);
            axios
                .post("http://carmeer.com/api/login", form, {
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
