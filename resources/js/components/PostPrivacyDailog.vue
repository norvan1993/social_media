<template>
    <v-dialog max-width="300px" full-width v-model="dailog">
        <v-icon flat slot="activator" small>{{ic}}</v-icon>

        <v-layout class="text-xs-center white" style="width:100%;" row wrap>
            <v-flex xs12 px-2>
                <v-btn flat class="info" @click="accept()" block :disabled="isDisabled">accept</v-btn>
            </v-flex>
            <v-flex xs12 px-2>
                <v-btn flat class="error" @click="reject()" block :disabled="isDisabled">reject</v-btn>
            </v-flex>
            <v-flex xs12 px-2>
                <v-btn flat class="warning" @click="ignore()" block :disabled="isDisabled">ignore</v-btn>
            </v-flex>
        </v-layout>
    </v-dialog>
</template>

<script>
export default {
    props: ["ic"],
    data() {
        return {
            isDisabled: false,
            dailog: false
        };
    },

    methods: {
        accept() {
            this.isDisabled = true;

            var form = new FormData();
            form.append("_method", "PUT");
            form.append("sender_id", this.user.id);
            axios({
                method: "post", //you can set what request you want to be
                url: "http://carmeer.com/api/friendship/accept",
                data: form,
                headers: {
                    Authorization:
                        "Bearer " + localStorage.getItem("access_token"),
                    "Content-Type": "multipart/form-data"
                }
            }).then(res => (this.dailog = false));
        },
        reject() {},
        ignore() {}
    }
};
</script>

<style lang="scss" scoped>
</style>

