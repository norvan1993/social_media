<template>
    <v-layout row wrap class="pa-3">
        <v-flex xs12 sm4 md2 class="black--text ma-2">
            <v-card class="grey lighten-4">
                <v-list-tile class="grow">
                    <v-list-tile-avatar color="grey darken-3">
                        <v-img
                            class="elevation-6"
                            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
                        ></v-img>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>Evan You</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    props: ["user", "profilePhoto", "profileRelationType"],
    data() {
        return {};
    },
    created: function() {
        axios
            .get("api/friendship/friend_list" + this.post.id + "/photos", {
                headers: {
                    Authorization:
                        "Bearer " + localStorage.getItem("access_token")
                }
            })
            .then(res => this.setPhotos(res.data));
    }
};
</script>

<style lang="scss" scoped>
</style>
