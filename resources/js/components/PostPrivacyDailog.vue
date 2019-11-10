<template>
    <v-dialog max-width="300px" full-width v-model="dialog">
        <v-icon flat slot="activator" small>{{ic}}</v-icon>

        <v-card>
            <v-card-title>
                <p class="title font-weight-thin mb-0">who can see your post ?</p>
            </v-card-title>
            <hr class="black--text" />
            <v-card-text>
                <v-form class="px-3">
                    <v-select
                        :items="items"
                        label="Status"
                        append-icon="fas fa-caret-down"
                        v-model="postPrivacyUpdated.status"
                    ></v-select>
                    <v-select
                        v-if="postPrivacyUpdated.status=='custom'"
                        :items="friends"
                        label="Status"
                        append-icon="fas fa-caret-down"
                    ></v-select>
                    <v-btn @click="dialog=false">cancel</v-btn>
                    <v-btn @click="changePostPrivacy()">ok</v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["postPrivacy"],
    data() {
        return {
            dialog: false,
            ic: "",
            items: ["private", "public", "friends", "custom"],
            postPrivacyUpdated: {
                status: "",
                id_list: ""
            }
        };
    },

    methods: {
        //changePostPrivacy
        changePostPrivacy() {
            this.$emit("postPrivacyUpdated", this.postPrivacyUpdated);
            //this.changePostPrivacyIcon(this.postPrivacyUpdated.status);
            this.dialog = false;
        },
        //changePostPrivacyIcon
        changePostPrivacyIcon(status) {
            switch (status) {
                case "public":
                    this.ic = "fa-globe";
                    break;
                case "private":
                    this.ic = "fa-lock";
                    break;
                case "friends":
                    this.ic = "fa-user-friends";
                    break;
                case "custom":
                    this.ic = "fa-star-of-life";
                    break;
            }
        }
    },
    created: function() {
        this.postPrivacyUpdated.status = this.postPrivacy.status;
        this.changePostPrivacyIcon(this.postPrivacyUpdated.status);
    },
    watch: {
        /*
        status: function(val) {
            changePostPrivacyIcon(val);
        }
        */
    }
};
</script>
<style lang="scss" scoped>
</style>

