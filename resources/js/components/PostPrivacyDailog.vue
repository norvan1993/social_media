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
                            v-for="friendId in friendsList"
                            class="mt-3 mb-3 ml-3 friendBlock black--text"
                        >
                            <friend
                                :friendId="friendId"
                                :selectStatus="selectStatus"
                                @click="changeSelectStatus()"
                            ></friend>
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
import Friend from "./Friend.vue";
export default {
    props: ["postPrivacy", "user"],
    data() {
        return {
            dialog: false,
            items: ["private", "public", "friends", "custom"],
            postPrivacyUpdated: {
                status: "",
                id_list: "",
                friendsList: []
            },
            selectStatus: false
        };
    },
    components: {
        friend: Friend
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
        },
        //handle Friend list
        handleFriendsList(friendsList) {
            this.friendsList = friendsList;
        },
        //changeBgColor
        changeSelectStatus() {
            if (this.selectStatus == false) {
                this.selectStatus = true;
            } else {
                this.selectStatus = false;
            }
        }
    },
    created: function() {
        var status = this.postPrivacy.status;
        this.postPrivacyUpdated.status = status;
        //get friendlist
        axios
            .get(
                "http://carmeer.com/api/friendship/" +
                    this.user.id +
                    "/friends_list",
                {
                    headers: {
                        Authorization:
                            "Bearer " + localStorage.getItem("access_token")
                    }
                }
            )
            .then(res => this.handleFriendsList(res.data.friendsList));
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
.friendBlock {
    display: inline-block;
    cursor: pointer;
}
</style>

