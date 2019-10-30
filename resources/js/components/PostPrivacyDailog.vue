<template>
    <v-dialog max-width="300px" full-width>
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
                        v-model="status"
                        @change="changePostPrivacyIcon(status)"
                    ></v-select>
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
            ic: "",
            items: ["private", "public", "friends", "custom"],
            status: ""
        };
    },

    methods: {
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
        this.status = this.postPrivacy.status;
        this.changePostPrivacyIcon(this.status);

        //this.status = this.postPrivacy.status;
        console.log(this.postPrivacy);
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

