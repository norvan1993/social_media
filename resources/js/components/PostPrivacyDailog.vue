<template>
    <v-dialog max-width="300px" full-width v-model="dialog">
        <v-icon flat slot="activator" small>{{icon}}</v-icon>

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
                    <div class="d-block filesContainer" v-if="postPrivacyUpdated.status=='custom'">
                        <div
                            v-for="(file, key)  in files"
                            class="mt-3 mb-3 ml-3 rounded shadow imageBlock"
                            :style="{backgroundImage:'url('+convertToData(file)+')'}"
                        >
                            <div class="imageOverlay"></div>
                            <img
                                src="/ic/cancel.png"
                                class="m-auto optionsArrow"
                                @click="removeImage(key)"
                            />
                        </div>
                    </div>
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
            this.$emit(
                "postPrivacyUpdated",
                this.postPrivacyUpdated.status,
                this.postPrivacyUpdated.id_list
            );

            this.dialog = false;
        },
        //changePostPrivacyIcon
        changePostPrivacyIcon(status) {
            switch (status) {
                case "public":
                    return "fa-globe";
                    break;
                case "private":
                    return "fa-lock";
                    break;
                case "friends":
                    return "fa-user-friends";
                    break;
                case "custom":
                    return "fa-star-of-life";
                    break;
            }
        }
    },
    created: function() {
        var status = this.postPrivacy.status;
        this.postPrivacyUpdated.status = status;
    },
    computed: {
        // a computed getter
        icon: function() {
            return this.changePostPrivacyIcon(this.postPrivacy.status);
        }
    },
    watch: {
        postPrivacyUpdated: function() {}
    }
};
</script>
<style lang="scss" scoped>
.filesContainer {
    width: 100%;
    height: 150px;
    background-color: white;
    padding-left: 10px;

    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
}
.filesContainer:after {
    content: "";
    display: inline-block;
    height: 100%;
    width: 10px;
}
</style>

