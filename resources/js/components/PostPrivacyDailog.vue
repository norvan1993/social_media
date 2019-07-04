<template>
    <v-dialog max-width="300px" full-width>
        <v-icon flat slot="activator" small>{{ic}}</v-icon>

        <v-card>
            <v-card-title primary-title>
                <h3 class="headline mb-0">who can see your post</h3>
            </v-card-title>
            <hr class="black--text" />
            <v-card-text>
                <v-form class="px-3">
                    <v-select
                        :items="items"
                        label="Status"
                        append-icon="fas fa-caret-down"
                        v-model="status"
                    ></v-select>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["ic", "postPrivacy"],
    data() {
        return {
            items: ["private", "public", "friends", "custom"],
            status: ""
        };
    },

    methods: {},
    created: function() {
        this.status = this.postPrivacy.status;
        console.log("hello");
    }
};
</script>
<style lang="scss" scoped>
</style>

